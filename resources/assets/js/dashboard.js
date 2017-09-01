/**
 * Created by feikwok on 1/9/17.
 */
import store from './store'

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