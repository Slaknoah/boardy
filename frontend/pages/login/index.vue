<template>
  <div class="auth-wrapper auth-v2">
    <b-row class="auth-inner m-0">

      <!-- Brand logo-->
      <b-link to="/" class="brand-logo">
        <logo />
        <h2 class="brand-text text-primary ml-1">
          boardy
        </h2>
      </b-link>
      <!-- /Brand logo-->

      <!-- Left Text-->
      <b-col
        lg="8"
        class="d-none d-lg-flex align-items-center p-5"
      >
        <div class="w-100 d-lg-flex align-items-center justify-content-center px-5">
          <b-img
            fluid
            :src="imgUrl"
            alt="Login V2"
          />
        </div>
      </b-col>
      <!-- /Left Text-->

      <!-- Login-->
      <b-col
        lg="4"
        class="d-flex align-items-center auth-bg px-2 p-lg-5"
      >
        <b-col
          sm="8"
          md="6"
          lg="12"
          class="px-xl-2 mx-auto"
        >
          <b-card-title
            title-tag="h2"
            class="font-weight-bold mb-1"
          >
            Добро пожаловать! 👋
          </b-card-title>
          <b-card-text class="mb-2">
            Пожалуйста, войдите в свой аккаунт для доступа
          </b-card-text>

          <!-- form -->
          <validation-observer v-slot="{ invalid, handleSubmit }" ref="loginValidation">
            <b-form
              class="auth-login-form mt-2"
              @submit.prevent
            >
              <!-- email -->
              <b-form-group
                label="Email"
                label-for="login-email"
              >
                <validation-provider
                  #default="{ errors }"
                  name="email"
                  mode="lazy"
                  rules="required|email|min:3"
                >
                  <b-form-input
                    id="login-email"
                    v-model="form.email"
                    autocomplete="username"
                    :state="errors.length > 0 ? false:null"
                    name="login-email"
                    placeholder="john@example.com"
                  />
                  <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
              </b-form-group>

              <!-- forgot password -->
              <b-form-group>
                <div class="d-flex justify-content-between">
                  <label for="login-password">Пароль</label>
                </div>
                <validation-provider
                  #default="{ errors }"
                  name="password"
                  mode="lazy"
                  rules="required|min:6|max:20"
                >
                  <b-input-group
                    class="input-group-merge"
                    :class="errors.length > 0 ? 'is-invalid':null"
                  >
                    <b-form-input
                      id="login-password"
                      v-model="form.password"
                      autocomplete="current-password"
                      :state="errors.length > 0 ? false:null"
                      class="form-control-merge"
                      :type="passwordFieldType"
                      name="login-password"
                      placeholder="············"
                    />
                    <b-input-group-append is-text>
                      <feather-icon
                        class="cursor-pointer"
                        :icon="passwordToggleIcon"
                        @click="togglePasswordVisibility"
                      />
                    </b-input-group-append>
                  </b-input-group>
                  <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
              </b-form-group>

              <!-- checkbox -->
              <b-form-group>
                <b-form-checkbox
                  id="remember-me"
                  v-model="form.remember_me"
                  name="checkbox-1"
                >
                  Запомнить меня
                </b-form-checkbox>
              </b-form-group>

              <!-- submit buttons -->
              <b-button
                type="submit"
                variant="primary"
                block
                :disabled="invalid"
                @click.prevent="handleSubmit(loginUser)"
              >
                <b-spinner small v-if="isLoading"/>
                Войти
              </b-button>
            </b-form>
          </validation-observer>

          <b-card-text class=" mt-2">
            <div>
              <strong>Логин:</strong> user@mail.com
            </div>

            <div>
              <strong>Пароль:</strong> password
            </div>
          </b-card-text>
        </b-col>
      </b-col>
    <!-- /Login-->
    </b-row>
  </div>
</template>

<script>
/* eslint-disable global-require */
import { ValidationProvider, ValidationObserver } from 'vee-validate'
import Logo from '@/components/Logo'
import { required, email } from '@validations'
import { togglePasswordVisibility } from '@core/mixins/ui/forms'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'
import { mapState } from 'vuex'

export default {
  layout: 'Full',
  middleware: 'guest',
  components: {
    Logo,
    ValidationProvider,
    ValidationObserver,
  },
  mixins: [togglePasswordVisibility],
  data() {
    return {
      sideImg: require('@/assets/images/pages/login-v2.svg'),
      // validation rulesimport store from '@/store/index'
      required,
      email,
      form: {
        email: '',
        password: '',
        remember_me: false
      },
      isLoading: false
    }
  },
  computed: {
    passwordToggleIcon() {
      return this.passwordFieldType === 'password' ? 'EyeIcon' : 'EyeOffIcon'
    },
    ...mapState( 'appConfig', [ 'layout' ] ),
    imgUrl() {
      if (this.layout.skin === 'dark') {
        // eslint-disable-next-line vue/no-side-effects-in-computed-properties
        this.sideImg = require('@/assets/images/pages/login-v2-dark.svg')
        return this.sideImg
      }
      return this.sideImg
    },
  },
  methods: {
    async loginUser () {
      this.isLoading = true;

      this.$auth.loginWith( 'laravelSanctum', { data: this.form } )
            .then( function() {
              this.isLoading = false;
              this.$toast({
                component: ToastificationContent,
                props: {
                  title: `Привет ${ this.$auth.user.name }!`,
                  text: 'Вы успешно авторизовали.',
                  icon: 'CoffeeIcon',
                  variant: 'success',
                }
              })

              this.$router.push('/');
            }.bind(this) )
            .catch( function(err) {
              this.isLoading = false;

              if( err.response && err.response.status === 401 ) {

                this.$toast({
                  component: ToastificationContent,
                  props: {
                    title: 'Ошибка!',
                    text: 'Неверные логин или пароль!',
                    icon: 'AlertCircleIcon',
                    variant: 'warning',
                  },
                })
              }
            }.bind(this))
    }
  },
}
</script>

<style lang="scss">
@import '@core/scss/vue/pages/page-auth.scss';
</style>
