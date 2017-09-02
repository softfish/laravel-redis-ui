@extends('redis-ui::app')

@section('content')
    <div id="dashboard">
<div id="hover-mask" v-if="maskon"></div>

    <message-box v-if="(maskon && actionMessage != null)"></message-box>
    <create-form v-if="(maskon && currentAction === 'create')"></create-form>
    <edit-form v-if="(maskon && currentAction === 'edit')"></edit-form>
    <filters-panel></filters-panel>


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
    <script src="{{ asset('js/dashboard.js') }}"></script>
@endsection