<template>
    <div id="message-box" v-if="(maskon && actionMessage != null)">
        <div class="panel"
             :class='{
                    "panel-default": (messageType == "info"),
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
</template>

<script>
    export default {

    }
</script>