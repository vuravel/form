import {Elements} from 'vuravel-elements'

const Form = {
  	install (Vue, options = {}) {
  		
		Vue.use(Elements)
		
		const files = require.context('./js/', true, /\.vue$/i)
		
		files.keys().map(key => {
			Vue.component('Vl'+key.split('/').pop().split('.')[0], files(key).default)
		})

	}
}
export default Form