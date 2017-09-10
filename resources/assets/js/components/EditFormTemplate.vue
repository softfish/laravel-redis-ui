<template>
    <div id="edit-form" v-if="(maskon && currentAction === 'edit')">
        <div class="panel panel-success">
            <div class="panel-heading">Edit Redis Data</div>
            <div class="panel-body">
                <table class="table">
                    <tbody>
                        <tr>
                            <td>Key</td>
                            <td>
                                <input class="form-control" v-model="editForm.keyname" readonly>
                            </td>
                        </tr>
                        <tr>
                            <td>Content</td>
                            <td>
                                <textarea class="form-control" v-model="editForm.content"></textarea>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="panel-footer">
                <div class="btn-group pull-right">
                    <button class="btn btn-primary" v-on:click="editRecord(editForm.keyname, editForm.content)">Update</button>
                    <button class="btn btn-default" v-on:click="closeEditForm()">X Close</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { mapState } from 'vuex'
    import store from '../../redis-ui/store'

    export default {
        data: function() {
            return {
                editPostUrl: "/redis-ui/api/edit",
            };
        },
        computed: mapState({
            editForm: state => state.editForm,
            maskon: state => state.maskon,
            currentAction: state => state.currentAction,
            actionMessage: state => state.actionMessage,
            messageType: state => state.messageType,
        }),
        methods: {
            editRecord: function(keyname, newValue) {
                axios.post(this.editPostUrl, {
                    keyname: keyname,
                    content: newValue,
                    database: store.getters.GET_DATABASE,
                }).then((response) => {
                    response = response.data;
                    if (response.success) {
                        store.commit('SET_MESSAGE_TYPE', 'info');
                        store.commit('SET_ACTION_MESSAGE', 'Record has been updated successfully!');
                        store.commit('SET_CREATE_FORM', []);
                        store.commit('searchNow');
                    } else {
                        store.commit('SET_MESSAGE_TYPE', 'error');
                        store.commit('SET_ACTION_MESSAGE', 'Failed to update the record!');
                    }
                });
            },
            closeEditForm: function() {
                store.commit('closePopUpBox');
            },
        }
    }
</script>