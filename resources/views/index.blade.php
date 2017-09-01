@extends('redis-ui::app')

@section('content')
    <div id="dashboard">
<div id="hover-mask" v-if="maskon"></div>


<div id="message-box" v-if="(maskon && actionMessage != null)">
    <div class="panel"
         :class='{  "panel-default": (messageType == "info"),
                    "panel-success": (messageType == "success"),
                    "panel-warning": (messageType == "confirming"),
                    "panel-danger": (messageType == "error")
                    }'
    >
        <div class="panel-heading">
            Ding!
        </div>
        <div class="panel-body">
            <p>@{{ actionMessage  }}</p>
        </div>
        <div class="panel-footer">
            <div class="btn-group pull-right">
                <button class="btn btn-danger" v-on:click="deleteRecord(targetKeyname)" v-if="(currentAction === 'delete')">Remove</button>
                <button class="btn btn-default" v-on:click="closeMessageBox()">X Close</button>
            </div>
        </div>
    </div>
</div>

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
                <button class="btn btn-default" v-on:click="closeMessageBox()">X Close</button>
            </div>
        </div>
    </div>
</div>

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
                <button class="btn btn-default" v-on:click="closeMessageBox()">X Close</button>
            </div>
        </div>
    </div>
</div>
        <div class="filters panel panel-default">
            <div class="panel-heading">
                <h5 class="pull-left col-sm-10"><i class="fa fa-filter" aria-hidden="true"></i> Redis Content Filter</h5>
                <div class="pull-right col-sm-2 input-group">
                    <span class="input-group-addon"><i class="fa fa-database" aria-hidden="true"></i> Database</span>
                    {{--<input class="form-control text-center" v-model="database">--}}
                    <div class="input">
                        <select class="form-control" v-model="database">
                            <option v-for="connection in availableDatabase">@{{ connection }}</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="panel-body">
                <div class="col-sm-6 pull-left">
                    <div class="input-group">
                        <span class="input-group-addon">Key</span>
                        <input class="form-control" v-model="filters.key">
                    </div>
                </div>
                <div class="col-sm-6 pull-left">
                    <div class="input-group">
                        <span class="input-group-addon">Content</span>
                        <input class="form-control" v-model="filters.content">
                    </div>
                </div>
            </div>
            <div class="panel-footer">
                <div class="col-sm-12">
                    <div class="page-control col-sm-6 pull-left">
                        <button class="btn btn-default" v-on:click="goBack()" :disabled="currentPage === 0">
                            <i class="fa fa-chevron-left" aria-hidden="true"></i> Preivous Page
                        </button>
                        <div class="btn">Page @{{ currentPage + 1 }}</div>
                        <button class="btn btn-default" v-on:click="goNext()" :disabled="rows.length < offset">
                            Next Page <i class="fa fa-chevron-right" aria-hidden="true"></i>
                        </button>
                    </div>

                    <div class="col-sm-6">
                        <div class="btn-group pull-right">
                            <button class="btn btn-danger" v-on:click="resetFilters()">Reset</button>
                            <button class="btn btn-primary" v-on:click="newSearch()"><i class="fa fa-search"></i> Search</button>
                        </div>
                        <div class="add-new btn btn-success pull-right" v-on:click="showCreateForm()">New</div>
                    </div>
                </div>
            </div>
        </div>

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
                filters: {
                    key: "",
                    content: ""
                },
                rows: [],
                filterPostUrl: "{{ url('redis-ui/api/filters') }}",
                currentPage: 0,
                previousPage: 0,
                nextPage: 1,
                offset: 20,
                deletePostUrl: "{{ url('redis-ui/api/delete/') }}",
                database: "cache",
                actionMessage: null,
                maskon: false,
                messageType: 'info',
                targetKeyname: null,
                currentAction: null,
                createForm: [],
                addPostUrl: "{{ url('redis-ui/api/create') }}",
                editForm: [],
                editPostUrl: "{{ url('redis-ui/api/edit') }}",
                availableDatabase: [
                    'cache',
                    'default'
                ],
            },
            created: function(){
                this.searchNow();
            },
            watch: {
                database: function () {
                    this.filters = {
                        key: "",
                        content: ""
                    };
                    this.searchNow();
                }
            },
            methods: {
                resetFilters: function () {
                    this.filters = {
                        key: "",
                        content: ""
                    };
                    this.currentPage = 0;
                    this.previousPage = 0;
                    this.nextPage = 1;

                    this.searchNow();
                },
                newSearch: function() {
                    this.currentPage = 0;
                    this.previousPage = 0;
                    this.nextPage = 1;
                    this.searchNow();
                },
                searchNow: function() {
                    axios.post(this.filterPostUrl, {
                        'filters': this.filters,
                        'currentPage': this.currentPage,
                        'previsouPage': this.previsouPage,
                        'nextPage': this.nextPage,
                        'offset': this.offset,
                        'database': this.database,
                    })
                    .then( (response) => {
                        response = response.data;
                        if (response.success) {
                            this.rows = response.data;
                        } else {
                            this.rows = [];
                        }
                    });
                },
                goNext: function() {
                    if (this.rows.length <= this.offset) {
                        this.currentPage ++;
                        this.nextPage ++;
                        this.searchNow();
                    }
                },
                goBack:function() {
                    if (this.currentPage > 0) {
                        this.currentPage --;
                        this.nextPage --;
                        this.searchNow();
                    }
                },
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
                closeMessageBox: function() {
                    this.maskon = false;
                    this.currentAction = null;
                    this.actionMessage = null;
                    this.targetKeyname = null;
                }
            }

        });
    </script>
@endsection