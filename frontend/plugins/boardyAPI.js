import AdvertsAPI from '~/api/adverts';
import TokensAPI from '~/api/tokens';

export default function({ $axios }, inject) {
  const api = {
    adverts: AdvertsAPI( $axios ),
    tokens: TokensAPI( $axios ),
  }

  inject('api', api);
}