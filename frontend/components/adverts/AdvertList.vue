<template>
  <div>
    <b-modal
      centered
      v-model="isAdvertModalOpen"
      hide-footer
      v-if="currentAdvert.id"
      :title="currentAdvert.title"
    >
      <b-row>
        <b-col
          cols="4"
          v-for="(image_link, index) in currentAdvert.images_links"
          :key="index"
        >
          <b-img fluid :src="image_link"></b-img>
        </b-col>
      </b-row>
      <div class="mt-2 mb-2">
        <h5 class="text-capitalize mb-75">Price</h5>
        <b-card-text>{{ currentAdvert.price | formatCurrency }}</b-card-text>
      </div>

      <div class="mt-2 mb-3">
        <h5 class="text-capitalize mb-75">Description</h5>
        <b-card-text>{{ currentAdvert.description }}</b-card-text>
      </div>
    </b-modal>

    <b-row>
      <b-col sm="4" cols="12">
        <b-card>
          <b-media
            vertical-align="center"
          >
            <template #aside>
              <b-avatar
                rounded
                size="64"
                variant="primary"
              >
                <span class="d-flex align-items-center">
                  <feather-icon
                    icon="SendIcon"
                    size="32"
                  />
                </span>
              </b-avatar>
            </template>
            <div>
              <h3 class="h1">{{ resourcesMeta.total }}</h3>
              <p class="text-muted mb-0">Размещенные объявления</p>
            </div>
          </b-media>
        </b-card>
      </b-col>
    </b-row>

    <b-card
      no-body
    >
      <list-head
        :items-per-page.sync="itemsPerPage"
        :per-page-options="perPageOptions"
        :search-query.sync="searchQuery"
      >
        <template #button>

        </template>

        <template #middle>
          <b-link @click="fetchAdverts">
            <feather-icon
              icon="RotateCwIcon"
              size="24"
              class="mx-1"
            />
          </b-link>
        </template>
      </list-head>

      <b-table
        responsive
        primary-key="id"
        :items="adverts"
        empty-text="You haven't created any advert"
        show-empty
        no-local-sorting
        :busy="isLoading"
        :sort-by.sync="orderBy"
        :sort-desc.sync="isOrderDirDesc"
        :fields="tableColumns"
      >
        <!-- Column: Title -->
        <template #cell(title)="data">
          <b-media
            vertical-align="center"
            class="mb-50 d-flex align-items-center"
          >
            <template #aside>
              <b-avatar
                size="64"
                @click.prevent="viewAdvert(data.item)"
                :src="data.item.image"
                rounded=""
              />
            </template>
            <h5 class="mb-0">
              <b-link
                @click.stop="viewAdvert(data.item)"
                class="font-weight-bold d-flex text-nowrap align-self-center"
              >
                {{ data.item.title }}
              </b-link>
            </h5>
          </b-media>
        </template>

        <!-- Column: Price -->
        <template #cell(price)="data">
          <div>
            {{ data.item.price | formatCurrency }}
          </div>
        </template>

        <!-- Column: Date -->
        <template #cell(created_at)="data">
          <div class="date-col">{{ data.item.created_at | date }}</div>
        </template>

        <!-- Column: Actions -->
        <template #cell(actions)="data">
          <div class="text-nowrap">
            <b-link @click="viewAdvert(data.item)">
              <feather-icon
                icon="EyeIcon"
                size="16"
                class="mx-1"
              />
            </b-link>
          </div>
        </template>
      </b-table>

      <list-foot
        :items-per-page="itemsPerPage"
        :data-meta="dataMeta"
        :resourcesMeta="resourcesMeta"
        :current-page.sync="currentPage"
      />
    </b-card>
  </div>
</template>

<script>
import useResourcesList from '@/components/useResourcesList'
import { ref, useContext, useFetch, watch, computed } from '@nuxtjs/composition-api'

export default {
  setup() {
    const { $config, $api } = useContext();
    const {
      currentPage,
      perPageOptions,
      itemsPerPage,
      dataMeta,
      orderBy,
      isOrderDirDesc,
      isLoading,
      searchQuery,
      resourcesMeta,
      addGlobalsToSearchParams
    } = useResourcesList( $config );

    const adverts              = ref([]);
    const tableColumns        = [
      { key: 'title', label: 'Заголовок', sortable: true },
      { key: 'price', label: 'Цена', sortable: true },
      { key: 'created_at', label: 'Дата создания', sortable: true },
      { key: 'actions', label: '' }
    ];

    const buildSearchAdverts = ( page ) => {
      let searchParams  = {};
      searchParams = addGlobalsToSearchParams( searchParams, page );

      return searchParams;
    }

    const fetchAdverts = async function( page = 1 ) {
      isLoading.value         = true;

      const searchParams      = buildSearchAdverts( page );
      const { data, meta }    = await $api.adverts.index( searchParams );
      adverts.value           = data;
      resourcesMeta.value     = meta;

      isLoading.value         = false;
    }
    fetchAdverts();

    watch( currentPage,   () => { fetchAdverts( currentPage.value ) })
    watch( orderBy,       () => { fetchAdverts() })
    watch( isOrderDirDesc,() => { fetchAdverts() })
    watch( searchQuery,   () => { fetchAdverts() })
    watch( itemsPerPage,  () => { fetchAdverts() })

    // Single advert
    const currentAdvert = ref({});
    const isAdvertModalOpen = ref(false);

    const viewAdvert = async advert => {
      const params = {
        fields: ['images_links', 'description']
      }
      const { data } = await $api.adverts.show(advert.id, params);

      currentAdvert.value = data;
      isAdvertModalOpen.value = true;
    }

    return {
      // View advert
      viewAdvert,
      isAdvertModalOpen,
      currentAdvert,

      currentPage,
      perPageOptions,
      itemsPerPage,
      dataMeta,
      orderBy,
      isOrderDirDesc,
      isLoading,
      searchQuery,
      tableColumns,
      resourcesMeta,
      adverts,
      fetchAdverts,
    }
  }
}
</script>