<template>
    <div id="message-box" v-if="(maskon && actionMessage != null)">
        <div class="panel"
             :class='{
                    "panel-default": (messageType == "info"),
                    "panel-success": (messageType == "success"),
                    "panel-warning": (messageType == "confirming"),
                    "panel-danger": (messageType == "error")
                    }'
        >
            <div class="panel-heading">
                Ding!
            </div>
            <div class="panel-body">
                <p>{{ actionMessage  }}</p>
            </div>
            <div class="panel-footer">
                <div class="btn-group pull-right">
                    <button class="btn btn-danger" v-on:click="deleteRecord(targetKeyname)" v-if="(currentAction === 'delete')">Remove</button>
                    <button class="btn btn-default" v-on:click="closeMessageBox()">X Close</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { mapState } from 'vuex'
    import store from '../store'

    export default {
        data: function() {
            return {
                deletePostUrl: "/redis-ui/api/delete",
            };
        },
        computed: mapState({
            actionMessage: state => state.actionMessage,
            maskon: state => state.maskon,
            targetKeyname: state => state.targetKeyname,
            currentAction: state => state.currentAction,
            messageType: state => state.messageType,
        }),
        methods: {
            deleteRecord: function(keyname) {
                axios.post(this.deletePostUrl, {
                    keyname: keyname,
                    database: store.getters.GET_DATABASE,
                }).then((response) => {
                    if (response.data.success) {
                        store.commit('SET_MESSAGE_TYPE', 'success');
                        store.commit('SET_ACTION_MESSAGE', 'Key ('+keyname+') has been removed successfully.');
                        store.commit('SET_MASK_ON', true);
                        store.commit('SET_CURRENT_ACTION', null); // hide the "remove" button.
                        store.commit('searchNow');
                    } else {
                        store.commit('SET_MESSAGE_TYPE', 'error');
                        store.commit('SET_ACTION_MESSAGE', 'Failed to remove key ('+keyname+')');
                        store.commit('SET_MASK_ON', true);
                    }
                });
            },
            closeMessageBox: function() {
                store.commit('closePopUpBox');
            }
        }
    }
</script>