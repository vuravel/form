<template>
    <vl-form-field v-bind="$_wrapperAttributes">
        <input
            v-model="component.value"
            class="vlFormControl"
            v-bind="$_attributes"
            v-on="$_events"
        />
    </vl-form-field>
</template>

<script>
import Field from '../mixins/Field'
import HasInputAttributes from '../mixins/HasInputAttributes'

export default {
    mixins: [Field, HasInputAttributes],
    computed: {
        $_attributes() {
            return {
                ...this.$_defaultFieldAttributes,
                ...this.$_defaultInputAttributes
            }
        }
    }
}
</script>

