@extends('redis-ui::app')

@section('content')
    <div id="dashboard">
<div id="hover-mask" v-if="maskon"></div>

    <messageBox></messageBox>
    <createForm></createForm>
    <editForm></editForm>
    <filtersPanel></filtersPanel>


        <div class="table-wrapper">
            <table class="result table table-striped table-bordered col-sm-12">
                <thead>
                    <tr>
                        <th class="col-sm-4">Key</th>
                        <th>Content</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="3" class="text-center" v-if="rows.length == 0">No records found</td>
                    </tr>
                    <tr v-for="row in rows">
                        <td class="col-sm-4">@{{ row.key }}</td>
                        <td>@{{ row.content }}</td>
                        <td class="col-sm-2 text-center">
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-warning" v-on:click="updateConfim(row.key, row.content)">Edit</button>
                                <button class="btn btn-danger" v-on:click="deleteConfirm(row.key)">Delete</button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>
@endsection

@section('header-scripts')
    <script src="https://unpkg.com/vue"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <link href="{{ asset('css/redis-ui-dashboard.css') }}" rel="stylesheet"/>
@endsection

@section('footer-script')
    <script>
        var app = new Vue({
            el: "#dashboard",
            data: {
                deletePostUrl: "{{ url('redis-ui/api/delete/') }}",
                actionMessage: null,
                maskon: false,
            },
            created: function(){
                this.searchNow();
            },
            methods: {
                deleteConfirm: function(keyname) {
                    this.messageType = 'confirming';
                    this.actionMessage = 'Are you sure you want to remove '+keyname+'?';
                    this.maskon = true;
                    this.currentAction = 'delete';
                    this.targetKeyname = keyname;
                },
                deleteRecord: function(keyname) {
                    axios.post(this.deletePostUrl, {
                        keyname: keyname
                    }).then((response) => {
                        if (response.data.success) {
                            this.messageType = 'success';
                            this.actionMessage = 'Key ('+keyname+') has been removed successfully.';
                            this.maskon = true;
                            this.searchNow();
                        } else {
                            this.messageType = 'error';
                            this.actionMessage ='Failed to remove key ('+keyname+')';
                            this.maskon = true;
                        }
                    });
                },
                closePopUpBox: function() {
                    this.maskon = false;
                    this.currentAction = null;
                    this.actionMessage = null;
                    this.targetKeyname = null;
                }
            }
        });
    </script>
@endsection