<template>
    <div class="filters panel panel-default">
        <div class="panel-heading">
            <h5 class="pull-left col-sm-10"><i class="fa fa-filter" aria-hidden="true"></i> Redis Content Filter</h5>
            <div class="pull-right col-sm-2 input-group">
                <span class="input-group-addon"><i class="fa fa-database" aria-hidden="true"></i> Database</span>
                <select v-model="database">
                    <option v-for="connection in availableDatabase">@{{ connection }}</option>
                </select>
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
</template>

<script>
    export default {
        data: {
            filters: {
                key: "",
                content: ""
            },
            filterPostUrl: "{{ url('redis-ui/api/filters') }}",
            currentPage: 0,
            previousPage: 0,
            nextPage: 1,
            offset: 20,
            database: "cache",
            availableDatabase: [
                'cache',
                'default'
            ],
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
            }
        }
    }
</script>