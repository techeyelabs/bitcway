import AppForm from '../app-components/Form/AppForm';

Vue.component('currency-form', {
    mixins: [AppForm],
    data: function() {
        return {
            form: {
                name:  '' ,
                is_custom:  false ,
                status:  false ,
                
            }
        }
    }

});