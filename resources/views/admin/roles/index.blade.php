@extends('admin.app')
@section('title',trans('messages.Roles'))

@section('content')
<div class="d-flex flex-column flex-column-fluid">

	<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
		<div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
			<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
				<h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">{{__('messages.Roles')}}</h1>
				<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
					<li class="breadcrumb-item text-muted">
						<a class="text-muted text-hover-primary">{{__('messages.Admin&Roles')}}</a>
					</li>
					<li class="breadcrumb-item">
						<span class="bullet bg-gray-400 w-5px h-2px"></span>
					</li>
					<li class="breadcrumb-item text-muted">{{__('messages.Roles')}}</li>
				</ul>
			</div>
			@can('create roles')
			<div class="d-flex align-items-center gap-2 gap-lg-3">
				<a class="btn btn-sm fw-bold btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_add_role">{{__('messages.Create')}}</a>
			</div>
			@endcan
		</div>
	</div>
	<div id="kt_app_content" class="app-content flex-column-fluid">
		<div id="kt_app_content_container" class="app-container container-xxl">
			<div class="card">
				<div class="card-body p-lg-17">
					<div id="" class="table-responsive">

						<table class="table align-middle gs-0 gy-4">
							<thead>
								<tr class="fw-bold text-muted bg-light">
									<th class="ps-4 min-w-300px rounded-start">{{__('messages.Name')}}</th>
									<th class="min-w-125px text-center">{{__('messages.Actions')}}</th>

								</tr>
							</thead>
							<tbody>
								@foreach($roles as $role)
								<tr>

									<td>{{$role->name}}</td>
									<td class="text-center">
										<a href="{{route('admin.roles.edit',$role->id)}}" class="btn btn-bg-light btn-color-muted btn-active-color-primary btn-sm px-4">{{__('messages.Edit')}}</a>
										@can('edit role')
										@endcan
										<!-- <a class="btn btn-bg-light btn-color-muted btn-active-color-danger btn-sm px-4">{{__('messages.Delete')}}</a> -->
									</td>

								</tr>
								@endforeach
							</tbody>
						</table>
					</div>

				</div>
			</div>
		</div>
	</div>
	<div class="modal fade " id="kt_modal_add_role" aria-modal="true" role="dialog">
		<!--begin::Modal dialog-->
		<div class="modal-dialog modal-fullscreen">
			<!--begin::Modal content-->
			<div class="modal-content">
				<!--begin::Modal header-->
				<div class="modal-header">
					<!--begin::Modal title-->
					<h2 class="fw-bold">{{__('messages.Create_Roles')}}</h2>
					<!--end::Modal title-->
					<!--begin::Close-->
					<div class="btn btn-icon btn-sm btn-active-icon-primary" data-kt-roles-modal-action="close" data-bs-dismiss="modal" aria-label="Close">
						<!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
						<span class="svg-icon svg-icon-1">
							<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
								<rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="currentColor"></rect>
								<rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="currentColor"></rect>
							</svg>
						</span>
						<!--end::Svg Icon-->
					</div>

					<!--end::Close-->
				</div>
				<!--end::Modal header-->
				<!--begin::Modal body-->
				<div class="modal-body scroll-y mx-lg-5 my-7">
					<!--begin::Form-->
					<form id="kt_modal_add_role_form" action="{{route('admin.roles.store')}}" class="form form-ajax fv-plugins-bootstrap5 fv-plugins-framework" method="post">
						@csrf
						<div class="d-flex flex-column scroll-y me-n7 pe-7" id="kt_modal_add_role_scroll" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_add_role_header" data-kt-scroll-wrappers="#kt_modal_add_role_scroll" data-kt-scroll-offset="300px" style="max-height: 347px;">
							<!--begin::Input group-->
							<div class="fv-row mb-10 fv-plugins-icon-container">
								<!--begin::Label-->
								<label class="fs-5 fw-bold form-label mb-2">
									<span class="required"> {{__('messages.Role_name')}}</span>
								</label>
								<!--end::Label-->
								<!--begin::Input-->
								<input class="form-control form-control-solid" placeholder="{{__('messages.Enter_a_role_name')}}" name="name" id="name" value="" required="">
								<!--end::Input-->
								<div class="fv-plugins-message-container invalid-feedback"></div>
							</div>
							<!--end::Input group-->
							<!--begin::Permissions-->
							<div class="fv-row">
								<!--begin::Label-->
								<label class="fs-5 fw-bold form-label mb-2">{{__('messages.Role_Permissions')}}</label>
								<!--end::Label-->
								<!--begin::Table-->
								<table class="table align-middle table-row-dashed fs-6 gy-5">
									<!--begin::Table body-->
									<tbody class="text-gray-600 fw-semibold">
										<!--begin::Table row-->
										<tr>
											<td class="text-gray-800">{{__('messages.Administrator_Access')}} <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" aria-label="Allows a full access to the system" data-bs-original-title="Allows a full access to the system" data-kt-initialized="1"></i>
											</td>
											<td>
												<!--begin::Checkbox-->
												<label onclick="selectAll()" class="form-check form-check-custom form-check-solid me-9">
													<input class="form-check-input permissions " type="checkbox" value="" id="kt_roles_select_all">
													<span class="form-check-label" for="kt_roles_select_all">{{__('messages.Select_all')}}</span>
												</label>
												<!--end::Checkbox-->
											</td>
										</tr>
									</tbody>
									<!--end::Table body-->
								</table>
								<div class="fv-row">
									<!--begin::Card-->
									<div class="card card-flush h-md-100">
										<!--begin::Card header-->
										<!--end::Card header-->
										<!--begin::Card body-->
										<div class="card-body pt-1">
											<!--begin::Users-->
											<!--end::Users-->
											<!--begin::Permissions-->
											<div class="row">

												@foreach($permissions as $setionName => $permissions)
												<span class="my-3"> {{ucwords(__("messages.$setionName"))}}</span>

												@foreach($permissions as $permission)
												<label class="col-4 mb-2">
													<input class="form-check-input permissions" type="checkbox" value="{{$permission->name}}" name="permissions[]">
													<span class="form-check-label fw-bold">
														{{$permission->name}}
														<!-- {{ session('lang') == 'en' 
															? ucwords(str_replace(['_', 'provider'], [' ', 'owner'], $permission->name)) 
															: ucwords(str_replace(['_', 'provider'], [' ', 'owner'], $permission->name_ar)) }} -->
													</span>

												</label>
												@endforeach
												@endforeach

											</div>
										</div>
										<!--end::Card body-->
									</div>
									<!--end::Card-->
								</div>
							</div>
							<!--end::Permissions-->
						</div>
						<!--begin::Actions-->
						<div class="text-center pt-15">
							<button type="reset" class="btn btn-light me-3" data-kt-roles-modal-action="cancel">{{__('messages.Retreat')}}</button>
							<button type="submit" class="btn btn-primary" data-kt-roles-modal-action="submit">
								<span class="indicator-label">{{__('messages.Save')}}</span>
								<span class="indicator-progress">{{__('messages.Please_wait...')}} <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
							</button>
						</div>

						<!--end::Actions-->
					</form>
					<!--end::Form-->
				</div>
				<!--end::Modal body-->
			</div>
			<!--end::Modal content-->
		</div>
		<!--end::Modal dialog-->
	</div>

	@endsection
	@section('js')
	<script>
		function selectAll() {
			let allInputs = document.querySelectorAll(".permissions");
			let kt_roles_select_all = document.getElementById('kt_roles_select_all');
			if (kt_roles_select_all.checked) {
				allInputs.forEach(item => {
					item.checked = true;
				});
			} else {
				// Loop through each checkbox and set checked to false
				allInputs.forEach(item => {
					item.checked = false;
				});
			}
		}
	</script>
	@endsection