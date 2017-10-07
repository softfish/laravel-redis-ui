/**
 * Created by feikwok on 1/9/17.
 */
import Vue from 'vue'
import Vuex from 'vuex'
import axios from 'axios'

// Vue.use(Vuex)

const store = new Vuex.Store({
    state: {
        rows: [],
        messageType: 'info',
        targetKeyname: null,
        currentAction: null,
        createForm: [],
        editForm: [],
        actionMessage: null,
        maskon: false,
        filters: {
            key: "",
            content: ""
        },
        filterPostUrl: "/redis-ui/api/filters",
        currentPage: 0,
        previousPage: 0,
        nextPage: 1,
        offset: 20,
        database: null,
        hasNextPage: false,
    },
    action: {

    },
    mutations: {
        SET_RESULT_ROWS: (state, list) => {
            state.rows = list;
        },
        SET_CREATE_FORM: (state, value) => {
            state.createForm = value;
        },
        SET_EDIT_FORM: (state, value) => {
            state.editForm = value;
        },
        SET_CURRENT_ACTION: (state, value) => {
            state.currentAction = value;
        },
        SET_TARGET_KEYNAME: (state, value) => {
            state.targetKeyname = value;
        },
        SET_MESSAGE_TYPE: (state, value) => {
            state.messageType = value;
        },
        SET_ACTION_MESSAGE: (state, value) => {
            state.actionMessage = value;
        },
        SET_MASK_ON: (state, value) => {
            state.maskon = value;
        },
        SET_FILTERS: (state, value) => {
            state.filters = value;
        },
        SET_CURRENT_PAGE: (state, value) => {
            state.currentPage = value;
        },
        SET_PREVIOUS_PAGE: (state, value) => {
            state.previous = value;
        },
        SET_NEXT_PAGE: (state, value) => {
            state.nextPage = value;
        },
        SET_OFFSET: (state, value) => {
            state.offset = value;
        },
        SET_DATABASE: (state, value) => {
            state.database = value;
        },
        SET_HAS_NEXT_PAGE: (state, value) => {
            state.hasNextPage = value;
        },
        closePopUpBox: (state) => {
            state.maskon = false;
            state.currentAction = null;
            state.actionMessage = null;
            state.targetKeyname = null;
        },
        searchNow: function(state) {
            axios.post(state.filterPostUrl, {
                'filters': state.filters,
                'currentPage': state.currentPage,
                'previousPage': state.previousPage,
                'nextPage': state.nextPage,
                'offset': state.offset,
                'database':state.database,
            })
                .then( (response) => {
                    response = response.data;
                    if (response.success) {
                        store.commit('SET_RESULT_ROWS', response.data);
                        store.commit('SET_HAS_NEXT_PAGE', response.hasNextPage);
                    } else {
                        this.rows = [];
                    }
                });
        },
        incrementCurrentPage: (state) => {
            state.currentPage ++;
        },
        incrementNextPage: (state) => {
            state.nextPage ++;
        },
        subtractiveCurrentPage: (state) => {
            state.currentPage --;
        },
        subtractiveNextPage: (state) => {
            state.nextPage --;
        },
        updateConfirm: (state, data) =>{
            state.currentAction = 'edit';
            state.maskon = true;
            state.editForm = {
                keyname: data.k,
                content: data.c
            };
        },
        deleteConfirm: (state, keyname) => {
            state.messageType = 'confirming';
            state.actionMessage = 'Are you sure you want to remove '+keyname+'?';
            state.maskon = true;
            state.currentAction = 'delete';
            state.targetKeyname = keyname;
        },
    },
    getters: {
        GET_RESULT_ROWS: state => {
            return state.rows;
        },
        GET_CREATE_FORM: state => {
            return state.createForm;
        },
        GET_EDIT_FORM: state => {
            return state.editForm;
        },
        GET_CURRENT_ACTION: state => {
            return state.currentAction;
        },
        GET_TARGET_KEYNAME: state => {
            return state.targetKeyname;
        },
        GET_MESSAGE_TYPE: state => {
            return state.messageType;
        },
        GET_ACTION_MESSAGE: state => {
            return state.actionMessage;
        },
        GET_MASK_ON: state => {
            return state.maskon;
        },
        GET_FILTER: state => {
            return state.filters;
        },
        GET_CURRENT_PAGE: state => {
            return state.currentPage;
        },
        GET_PREVIOUS_PAGE: state => {
            return state.previous;
        },
        GET_NEXT_PAGE: state => {
            return state.nextPage;
        },
        GET_HAS_NEXT_PAGE: state => {
            return state.hasNextPage;
        },
        GET_OFFSET: state => {
            return state.offset;
        },
        GET_DATABASE: state => {
            return state.database;
        },
    }
});

export default store