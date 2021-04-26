<template>
  <div class="pb-5">
    <h1 class="mb-2">Активность</h1>

    <b-modal
      id="modal-success"
      ok-only
      ok-variant="success"
      ok-title="Done"
      modal-class="modal-success"
      centered
      v-model="showTokenResponseModal"
      title="Please copy and save token"
    >
      <b-card-text>
        {{ tokenResponse }}
      </b-card-text>
    </b-modal>

    <b-modal
      id="token-modal"
      ref="my-modal"
      title="Create new API token"
      ok-title="Create token"
      centered
      v-model="showTokenModal"
      cancel-variant="outline-secondary"
      @show="resetModal"
      @hidden="resetModal"
      @ok="handleOk"
    >
      <p>Создайте токен доступа, чтобы вы могли создавать рекламу и отслеживать данные. Скопируйте токен после его создания. Если вы этого не сделаете, он будет утерян навсегда.</p>
      <form
        ref="tokenFormDOM"
        @submit.stop.prevent="handleSubmitTokenForm"
      >
        <b-form-group
          :state="nameState"
          label="Введите название вашего токена"
          label-for="name-input"
          invalid-feedback="Требуется название токена"
        >
          <b-form-input
            id="name-input"
            v-model="tokenForm.token_name"
            :state="nameState"
            placeholder="e.g. Staging Token"
            required
          />
        </b-form-group>
      </form>
    </b-modal>

    <advert-list></advert-list>

    <b-card class="mt-2">
      <div class="text-center py-2">
        <div v-if="tokens.length" class="mb-2">
          <b-button
            size="md"
            class="mr-1"
            variant="outline-primary"
            v-for="token in tokens"
            :key="token.id">
            {{ token.name | title }}

            <feather-icon
              icon="XIcon"
              size="16"
              class="ml-25"
              @click.prevent="revokeToken(token)"
            />
          </b-button>
        </div>

        <div v-else>
          <b-button class="p-1 mb-1">
            <feather-icon
              icon="CodeIcon"
              size="24"
            />
          </b-button>
          <h3>Нет токенов API для отображения</h3>
          <p>Вы еще не создали токены API</p>
        </div>
        <!-- button -->
        <b-button
          variant="primary"
          v-b-modal.token-modal
        >
          Создать новый токен
        </b-button>
      </div>
    </b-card>
  </div>
</template>

<script>
import { ref, useContext } from '@nuxtjs/composition-api'
import AdvertList from '@/components/adverts/AdvertList';

export default {
  layout: 'Main',
  middleware: 'auth',
  components: {
    AdvertList
  },
  setup() {
    const { $api } = useContext();

    // Token form
    const tokens = ref([]);
    const showTokenModal = ref(false);
    const showTokenResponseModal = ref(false);
    const tokenResponse = ref(null);

    const tokenForm = ref({
      token_name: ''
    })
    const nameState = ref(null);

    const fetchTokens = async () => {
      const response = await $api.tokens.index();
      tokens.value = response.tokens;
    }
    fetchTokens();

    const revokeToken = async token => {
      await $api.tokens.revoke( token.id )
        .then( () => {
          fetchTokens();
        });
    }

    const handleSubmitTokenForm = async () => {
      if (!tokenForm.value.token_name) {
        nameState.value = false;
        return
      }

      const response = await $api.tokens.create(tokenForm.value);
      tokenResponse.value = response.token;

      showTokenModal.value = false;
      showTokenResponseModal.value = true;
      fetchTokens();
    };

    const handleOk = bvModalEvt => {
      bvModalEvt.preventDefault();

      handleSubmitTokenForm()
    };

    const resetModal = () => {
      nameState.value = null;
      tokenForm.value.token_name = ''
    }

    return {
      // Tokens,
      tokens,
      tokenForm,
      revokeToken,
      nameState,
      handleOk,
      handleSubmitTokenForm,
      resetModal,
      showTokenModal,
      showTokenResponseModal,
      tokenResponse,
    }
  }
}
</script>

<style lang="scss">
@import '@core/scss/vue/libs/vue-select.scss';
.date-col { min-width: 5em;}
.label-selector { width: 200px; }
</style>