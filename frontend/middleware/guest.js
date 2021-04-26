export default function( context ) {
  if ( context.$auth.loggedIn ) {
    return context.redirect('/');
  }
}