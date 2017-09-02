/**
 * Created by feikwok on 1/9/17.
 */
import store from './redis-ui/store'

var messageBox = require('./components/redis-ui/MessageBoxTemplate.vue');
var createForm = require('./components/redis-ui/CreateFormTemplate.vue');
var editForm = require('./components/redis-ui/EditFormTemplate.vue');
var filtersPanel = require('./components/redis-ui/FilterPanelTemplate.vue');

new Vue({
    el: '#dashboard',
    store,
    components: {
        messageBox,
        createForm,
        editForm,
        filtersPanel,
    }
})