<x-master-layout>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-block card-stretch">
                    <div class="card-body p-0">
                        <div class="d-flex justify-content-between align-items-center p-3 flex-wrap gap-3">
                            <h5 class="fw-bold">{{ $pageTitle }}</h5>
                            @if(auth()->user()->can('service zone add'))
                                <a href="{{route('servicezone.create')}}" class="float-end btn btn-sm btn-primary"><i class="fa fa-plus"></i> {{__('messages.add_secteurs_ia')}}</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <select name="column_status" id="column_status" class="form-control select2js" data-filter="select" style="width: 200px">
                                    <option value="">{{__('messages.all')}}</option>
                                    <option value="1" {{($filter['status'] == '1') ? "selected='selected'" : '' }}>{{__('messages.active')}}</option>
                                    <option value="0" {{($filter['status'] == '0') ? "selected='selected'" : '' }}>{{__('messages.inactive')}}</option>
                                </select>
                                <button type="button" class="btn btn-info mx-2" data-filter="apply">{{__('messages.apply')}}</button>
                                <button type="button" class="btn btn-secondary" data-filter="reset">{{__('messages.reset')}}</button>
                            </div>
                            <div class="table-action">
                                <form id="quick-action-form" class="d-flex align-items-center">
                                    @csrf
                                    <select name="action_type" class="form-control select2js col-md-3" id="quick-action-type" style="width:100%">
                                        <option value="">{{__('messages.no_action')}}</option>
                                        <option value="change-status">{{__('messages.change_status')}}</option>
                                        <option value="delete">{{__('messages.delete')}}</option>
                                        <option value="restore">{{__('messages.restore')}}</option>
                                        <option value="permanently-delete">{{__('messages.permanent_dlt')}}</option>
                                    </select>
                                    <div class="select-status d-none ml-2" id="change-status-action">
                                        <select name="status" class="form-control select2js" id="status" style="width:100%">
                                            <option value="1">{{__('messages.active')}}</option>
                                            <option value="0">{{__('messages.inactive')}}</option>
                                        </select>
                                    </div>
                                    <button id="quick-action-apply" class="btn btn-primary ml-2" data-ajax="true" 
                                        data--submit="{{route('servicezone.bulk-action')}}" 
                                        data-datatable="reload" data-confirmation='true' 
                                        data-title="{{__('messages.are_you_sure',['title' => __('messages.category') ])}}"
                                        title="{{__('messages.apply')}}" type="button">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table id="datatable" class="table table-striped border">
                                <thead>
                                    <tr class="ligth ligth-data">
                                        <th width="15">
                                            <input type="checkbox" name="select_all_table" id="select-all-table" onclick="selectAllTable(this)">
                                        </th>
                                        <th>{{__('messages.name')}}</th>
                                        <th>{{__('messages.ai_sector')}}</th>
                                        <th>{{__('messages.ai_model')}}</th>
                                        <th>{{__('messages.providers')}}</th>
                                        <th>{{__('messages.services')}}</th>
                                        <th>{{__('messages.status')}}</th>
                                        <th>{{__('messages.action')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @section('bottom_script')
        <script type="text/javascript">
            (function($) {
                "use strict";
                $(document).ready(function(){
                    var table = $('#datatable').DataTable({
                        processing: true,
                        serverSide: true,
                        autoWidth: false,
                        responsive: true,
                        'iDisplayLength': 25,
                        order: [ [1, 'desc'] ],
                        ajax: {
                            url: "{{route('servicezone.index_data')}}",
                            type: "GET"
                        },
                        language: {
                            searchPlaceholder: "{{__('messages.search')}}",
                            processing: '<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>'
                        },
                        columns: [
                            {data: 'check', name: 'check', orderable: false, searchable: false, width: '15'},
                            {data: 'name', name: 'name'},
                            {data: 'ai_sector', name: 'ai_sector', orderable: false, searchable: false},
                            {data: 'ai_model', name: 'ai_model', orderable: false, searchable: false},
                            {data: 'providers', name: 'providers', orderable: false, searchable: false},
                            {data: 'service_count', name: 'service_count', orderable: false, searchable: false},
                            {data: 'status', name: 'status'},
                            {data: 'action', name: 'action', orderable: false, searchable: false},
                        ]
                    });

                    function resetQuickAction () {
                        const actionValue = $('#quick-action-type').val();
                        if (actionValue != '') {
                            $('#quick-action-apply').removeAttr('disabled');

                            if(actionValue == 'change-status') {
                                $('.select-status').removeClass('d-none');
                            } else {
                                $('.select-status').addClass('d-none');
                            }
                        } else {
                            $('#quick-action-apply').attr('disabled', true);
                            $('.select-status').addClass('d-none');
                        }
                    }

                    $('#quick-action-type').change(function () {
                        resetQuickAction()
                    });

                    resetQuickAction();

                    // Filtres
                    $('[data-filter="apply"]').on('click', function() {
                        $('#column_status').trigger('change');
                    });

                    $('[data-filter="reset"]').on('click', function() {
                        $('#column_status').val('').trigger('change');
                    });

                    $('#column_status').change(function() {
                        table.columns(6).search(this.value).draw();
                    });

                    // Stocker la référence du tableau pour les filtres
                    window.renderedDataTable = table;
                });
            })(jQuery);
        </script>
    @endsection
</x-master-layout>
