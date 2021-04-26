export default $axios => ({
  async index( params ) {
    try {
      const response = await $axios.get( '/api/v1/adverts', {
        params: params
      });

      return response.data;
    } catch(err) {
      throw err;
    }
  },

  async show( advertID, params ) {
    try {
      const response = await $axios.get( `/api/v1/adverts/${advertID}`, {
        params: params
      });

      return response.data;
    } catch(err) {
      throw err;
    }
  }
});