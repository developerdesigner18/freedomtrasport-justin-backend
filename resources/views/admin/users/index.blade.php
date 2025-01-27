@extends('admin.layouts.app')

@section('title', 'Users')

@section('content')
    <div id="type-model" class="modal fade" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- BEGIN: Modal Header -->
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i class="fa fa-times" aria-hidden="true"></i></button>
                </div> <!-- END: Modal Header -->
                <!-- BEGIN: Modal Body -->
                <div class="modal-body" id="typeDetails">
                </div>
            </div>
        </div>
    </div>

    <!-- Start Page content -->
    <section class="content">
        {{-- <div class="container-fluid"> --}}

        <div class="row">
            <div class="col-12">
                <div class="box">

                    <div class="box-header with-border">
                        <div class="row text-right">

                            <div class="col-8 col-md-3">
                                <div class="form-group">
                                    <input type="text" name="search" id="search_keyword" class="form-control"
                                           placeholder="@lang('view_pages.enter_keyword')">
                                </div>
                            </div>

                            <div class="col-4 col-md-2 text-left">
                                <button id="search" class="btn btn-success btn-outline btn-sm py-2" type="submit">
                                    @lang('view_pages.search')
                                </button>
                            </div>


                            <div class="col-md-7 text-center text-md-right">
                                @if(auth()->user()->can('add-user'))
                                    <a href="{{ url('users/create') }}" class="btn btn-primary btn-sm">
                                        <i class="mdi mdi-plus-circle mr-2"></i>@lang('view_pages.add_user')</a>
                                    <!--  <a class="btn btn-danger">
                                                    Export</a> -->
                                @endif
                            </div>
                        </div>
                        <!-- <div class="box-controls pull-right">
                        <div class="lookup lookup-circle lookup-right">
                          <input type="text" name="s">
                        </div>
                      </div> -->

                    </div>

                    <div id="js-user-partial-target">
                        <include-fragment src="users/fetch">
                            <span style="text-align: center;font-weight: bold;"> @lang('view_pages.loading')</span>
                        </include-fragment>
                    </div>


                </div>
            </div>

        </div>
        <!-- container -->

        {{-- </div> --}}
        <!-- content -->

        <script src="{{ asset('assets/js/fetchdata.min.js') }}"></script>
        <script>
            var search_keyword = '';
            $(function () {
                $('body').on('click', '.pagination a', function (e) {
                    e.preventDefault();
                    var url = $(this).attr('href');
                    $.get(url, $('#search').serialize(), function (data) {
                        $('#js-user-partial-target').html(data);
                    });
                });

                $('#search').on('click', function (e) {
                    e.preventDefault();
                    search_keyword = $('#search_keyword').val();

                    fetch('users/fetch?search=' + search_keyword)
                        .then(response => response.text())
                        .then(html => {
                            document.querySelector('#js-user-partial-target').innerHTML = html
                        });
                });

            });

            $(document).on('click', '.sweet-delete', function (e) {
                e.preventDefault();

                let url = $(this).attr('data-url');

                swal({
                    title: "Are you sure to delete ?",
                    type: "error",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Delete",
                    cancelButtonText: "No! Keep it",
                    closeOnConfirm: false,
                    closeOnCancel: true
                }, function (isConfirm) {
                    if (isConfirm) {
                        swal.close();

                        $.ajax({
                            url: url,
                            cache: false,
                            success: function (res) {

                                fetch('users/fetch?search=' + search_keyword)
                                    .then(response => response.text())
                                    .then(html => {
                                        document.querySelector('#js-user-partial-target')
                                            .innerHTML = html
                                    });

                                $.toast({
                                    heading: '',
                                    text: res,
                                    position: 'top-right',
                                    loaderBg: '#ff6849',
                                    icon: 'success',
                                    hideAfter: 5000,
                                    stack: 1
                                });
                            }
                        });
                    }
                });
            });

            $(document).on('click', '.btnExtra', function () {
                let user_id = $(this).data('user_id');
                let type = $(this).data('type');

                $.ajax({
                    url: "{{route('admin.get.user.extra.details')}}",
                    data:{
                        "user_id":user_id,
                        "type":type,
                        "_token":"{{csrf_token()}}",
                    },
                    type:'get',
                    async:true,
                    cache: false,
                    beforeSend:function (){
                        $('#typeDetails').empty();
                    },
                    success: function (res) {

                        $('#typeDetails').html(res);
                        $('#type-model').modal('show');
                    }
                });

            })
        </script>
@endsection
