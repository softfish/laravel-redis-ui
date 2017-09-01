<template>
    <div id="create-form" v-if="(maskon && currentAction === 'create')">
        <div class="panel panel-success">
            <div class="panel-heading">Create a new Redis Data</div>
            <div class="panel-body">
                <table class="table">
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
                </table>
            </div>
            <div class="panel-footer">
                <div class="btn-group pull-right">
                    <button class="btn btn-primary" v-on:click="addRecord(createForm.keyname, createForm.content)">Submit</button>
                    <button class="btn btn-default" v-on:click="closePopUpBox()">X Close</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data: {
            addPostUrl: "{{ url('redis-ui/api/create') }}",
        },
        methods: {
            showCreateForm: function(){
                this.currentAction = 'create';
                this.maskon = true;
            },
            addRecord: function(keyname, value) {
                axios.post(this.addPostUrl, {
                    keyname: keyname,
                    content: value
                }).then((response) => {
                    response = response.data;
                    if (response.success) {
                        this.messageType = 'info';
                        this.actionMessage = 'New record created successfully!';
                        this.createForm = [];
                        this.searchNow();
                    } else {
                        this.messageType = 'error';
                        this.actionMessage = 'Failed to create new record!';
                    }
                });
            },
        }
    }
</script>