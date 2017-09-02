/**
 * Created by feikwok on 1/9/17.
 */
import Vue from 'vue'
import Vuex from 'vuex'
import axios from 'axios'

Vue.use(Vuex)

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
        closePopUpBox: () => {
            state.maskon = false;
            state.currentAction = null;
            state.actionMessage = null;
            state.targetKeyname = null;
        }
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
    }
});

export default store