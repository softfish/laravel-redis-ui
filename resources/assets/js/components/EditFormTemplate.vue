<template>
    <div id="edit-form" v-if="(maskon && currentAction === 'edit')">
        <div class="panel panel-success">
            <div class="panel-heading">Edit Redis Data</div>
            <div class="panel-body">
                <table class="table">
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
                </table>
            </div>
            <div class="panel-footer">
                <div class="btn-group pull-right">
                    <button class="btn btn-primary" v-on:click="editRecord(editForm.keyname, editForm.content)">Update</button>
                    <button class="btn btn-default" v-on:click="closePopUpBox()">X Close</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data: {
            editPostUrl: "{{ url('redis-ui/api/edit') }}",
        },
        methods: {
            updateConfim: function(keyname, value) {
                this.currentAction = 'edit';
                this.maskon = true;
                this.editForm = {
                    keyname: keyname,
                    content: value
                };
            },
            editRecord: function(keyname, newValue) {
                axios.post(this.editPostUrl, {
                    keyname: keyname,
                    content: newValue
                }).then((response) => {
                    response = response.data;
                    if (response.success) {
                        this.messageType = 'info';
                        this.actionMessage = 'Record has been updated successfully!';
                        this.createForm = [];
                        this.searchNow();
                    } else {
                        this.messageType = 'error';
                        this.actionMessage = 'Failed to update the record!';
                    }
                });
            },
        }
    }
</script>