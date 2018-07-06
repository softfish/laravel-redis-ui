<template>
    <div class="filters panel panel-default">
        <div class="panel-heading">
            <h5 class="pull-left col-sm-10"><i class="fa fa-filter" aria-hidden="true"></i> Redis Content Filter</h5>
            <div class="pull-right col-sm-2 input-group">
                <span class="input-group-addon"><i class="fa fa-database" aria-hidden="true"></i> Database</span>
                <div class="input">
                    <select class="form-control" v-model="database">
                        <option v-for="connection in availableDatabase">{{ connection }}</option>
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
                    <div class="col-sm-4">
                        <div class="input-group">
                            <span class="input-group-addon">Offset:</span>
                            <input class="form-control" v-model="offset">
                        </div>
                    </div>
                    <button class="btn btn-default" v-on:click="goBack()" :disabled="currentPage === 0">
                        <i class="fa fa-chevron-left" aria-hidden="true"></i> Preivous Page
                    </button>
                    <div class="btn">Page {{ currentPage + 1 }}</div>
                    <button class="btn btn-default" v-on:click="goNext()" :disabled="!hasNextPage">
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
    import { mapState } from 'vuex'
    import store from '../store'

    export default {
        data: function() {
            return {
                availableDatabase: [],
                getDBUrl: '/redis-ui/api/get-db',
                database: 'default',
                offset: 20,
            };
        },
        created: function() {
            this.getDB();
            store.commit('searchNow');
        },
        computed: mapState ({
            rows: state => state.rows,
            filters: state => state.filters,
            currentPage: state => state.currentPage,
            hasNextPage: state => state.hasNextPage,
        }),
        watch: {
            database: function (value) {
                store.commit('SET_FILTERS',{
                    key: "",
                    content: ""
                });
                store.commit('SET_DATABASE', value);
                store.commit('searchNow');
            },
            offset: function(value) {
                store.commit('SET_OFFSET', parseInt(value));
            },
        },
        methods: {
            showCreateForm: function(){
                store.commit('SET_CURRENT_ACTION', 'create');
                store.commit('SET_MASK_ON', true);
            },
            resetFilters: function () {
                store.commit('SET_FILTERS',{
                    key: "",
                    content: ""
                });
                store.commit('SET_CURRENT_PAGE', 0);
                store.commit('SET_PREVIOUS_PAGE', 0);
                store.commit('SET_NEXT_PAGE', 1);
                store.commit('searchNow');
            },
            newSearch: function() {
                store.commit('SET_CURRENT_PAGE', 0);
                store.commit('SET_PREVIOUS_PAGE', 0);
                store.commit('SET_NEXT_PAGE', 1);
                store.commit('searchNow');
            },
            goNext: function() {
                if (store.getters.GET_RESULT_ROWS.length <= store.getters.GET_OFFSET) {
                    store.commit('incrementCurrentPage');
                    store.commit('incrementNextPage');
                    store.commit('searchNow');
                }
            },
            goBack:function() {
                if (this.currentPage > 0) {
                    store.commit('subtractiveCurrentPage');
                    store.commit('subtractiveNextPage');
                    store.commit('searchNow');
                }
            },
            getDB: function() {
                axios.get(this.getDBUrl)
                .then( (response) => {
                    response = response.data;
                    if (response.success) {
                        this.availableDatabase = response.databases;
                    }
                });
            },
        }
    }
</script>