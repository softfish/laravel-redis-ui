<template>
    <div id="create-form">
        <div class="panel panel-success">
            <div class="panel-heading">Create a new Redis Data</div>
            <div class="panel-body">
                <table class="table">
                    <tbody>
                        <tr>
                            <td>Key</td>
                            <td>
                                <input class="form-control" v-model="createForm.keyname">
                            </td>
                        </tr>
                        <tr>
                            <td>Content</td>
                            <td>
                                <textarea class="form-control" v-model="createForm.content"></textarea>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="panel-footer">
                <div class="btn-group pull-right">
                    <button class="btn btn-primary" v-on:click="addRecord(createForm.keyname, createForm.content)">Submit</button>
                    <button class="btn btn-default" v-on:click="closeCreateForm()">X Close</button>
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
                addPostUrl: "/redis-ui/api/create",
            };
        },
        computed: mapState({
            createForm: state => state.createForm,
            maskon: state => state.maskon,
            currentAction: state => state.currentAction,
            actionMessage: state => state.actionMessage,
            messageType: state => state.messageType,
        }),
        methods: {
            addRecord: function(keyname, value) {
                axios.post(this.addPostUrl, {
                    keyname: keyname,
                    content: value,
                    database: store.getters.GET_DATABASE,
                }).then((response) => {
                    response = response.data;
                    if (response.success) {
                        store.commit('SET_MESSAGE_TYPE', 'info')
                        store.commit('SET_ACTION_MESSAGE', 'New record created successfully!');
                        store.commit('SET_CREATE_FORM', []);
                        store.commit('searchNow');
                    } else {
                        store.commit('SET_MESSAGE_TYPE', 'error')
                        store.commit('SET_ACTION_MESSAGE', 'Failed to create new record!');
                    }
                });
            },
            closeCreateForm: function(){
                store.commit('closePopUpBox');
            }
        }
    }
</script>