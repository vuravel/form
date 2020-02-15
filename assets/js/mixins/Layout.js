import Component from './Component'
import {Element} from 'vuravel-elements'

export default {
    mixins: [ Component ],
    data: () => ({
        components: []
    }),

    computed: {
        $_customLayoutAttributes(){
            return {}
        },
        $_layoutWrapperAttributes(){
            return Object.assign({
                class: this.$_classes,
                style: this.$_elementStyles
            }, this.$_displayIdAttribute ? { id: this.component.id } : {},
            this.$_customLayoutAttributes)
        },
    },

	methods: {
        $_attributes(component) { return this.$_defaultLayoutAttributes(component) },
        $_defaultLayoutAttributes(component) {
            return {
                key: component.id,
                is: 'Vl' + component.component,
                vcomponent: component,
                vuravelid: this.vuravelid || this.$_elementId() //this.$_elementId is for FormInner or FormPanel outside a form,
            }
        },
        $_marginClass(component){
            return component.component == 'Rows' ? '' : 'vlFormMargin'
        },
        $_getPathById(id, path){
            path = path || ''
            var result = Component.methods.$_getPathById.call(this, id, path)
            if(result) return result
            for(const [key,item] of this.components.entries()){
                result = item.$_getPathById(id, path + '.components[' + key + ']')
                if(result) return result
            }            
        },
        $_state(state){
            if(_.isString(state)){
                return Element.methods.$_state.call(this, state)
            }else{
                Element.methods.$_state.call(this, state)
                this.components.forEach( item => { item.$_state(state) })
            }
        },
        $_toggle(toggleId){
            Element.methods.$_toggle.call(this, toggleId)
            if(!this.$_state('disabled'))
                this.components.forEach( item => item.$_toggle(toggleId) )
        },
        $_fillRecursive(jsonFormData){
            if(!this.$_hidden)
                this.components.forEach( item => item.$_fillRecursive(jsonFormData) )
        },
        $_validate(errors) {
            this.components.forEach( item => item.$_validate(errors) )
        },
        $_getErrors(errors) {
            this.components.forEach( item => item.$_getErrors(errors) )
        },
        $_resetSort(exceptId) {
            this.components.forEach( item => item.$_resetSort(exceptId) )
        },
        $_deliverJsonTo(componentId, json){
            this.components.forEach( item => item.$_deliverJsonTo(componentId, json) )
        },
	},
    created() {
        this.components = this.component.components
    }

}
