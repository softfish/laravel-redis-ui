/**
 * Created by feikwok on 1/9/17.
 */
import store from './store'

import MessageBox from '../components/redis-ui/MessageBoxTemplate.vue'
import CreateForm from '../components/redis-ui/CreateFormTemplate.vue'
import EditForm from '../components/redis-ui/EditFormTemplate.vue'
import FiltersPanel from '../components/redis-ui/FilterPanelTemplate.vue'

import { mapState } from 'vuex'

new Vue({
    el: '#dashboard',
    store,
    components: {
        'message-box': MessageBox,
        'create-form': CreateForm,
        'edit-form': EditForm,
        'filters-panel': FiltersPanel,
    },
    computed: mapState({
        maskon: state => state.maskon,
        rows: state => state.rows,
        currentAction: state => state.currentAction,
        actionMessage: state => state.actionMessage,
    }),
    methods: {
        updateConfirm: function(key, content) {
            store.commit('updateConfirm', { k: key, c: content});
        },
        deleteConfirm: function(key) {
            store.commit('deleteConfirm', key);
        }
    },
})