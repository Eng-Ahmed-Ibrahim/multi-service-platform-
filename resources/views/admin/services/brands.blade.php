@extends('admin.app')
@section('title',$service->name)
@section('content')
<div class="d-flex flex-column flex-column-fluid">
	<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
		<div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
			<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
				<h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">{{$service->name}}</h1>
				<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
					<li class="breadcrumb-item text-muted">
						<a class="text-muted text-hover-primary">{{__('messages.Services')}}</a>
					</li>
					<li class="breadcrumb-item">
						<span class="bullet bg-gray-400 w-5px h-2px"></span>
					</li>
					<li class="breadcrumb-item text-muted">{{__("messages.Brands")}}</li>
				</ul>
			</div>
			<div class="d-flex align-items-center gap-2 gap-lg-3">


				<a class="btn btn-sm fw-bold btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_add_user">{{__('messages.Create')}}</a>
			</div>
		</div>
	</div>
	<div id="kt_app_content" class="app-content flex-column-fluid">
		<div id="kt_app_content_container" class="app-container container-xxl">
			<div class="card">
				<div class="card-body p-lg-17">
				<div id="" class="table-responsive">

					<table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_ecommerce_sales_table">
						<thead>
							<tr class=" text-gray-400 fw-bold fs-7 text-uppercase gs-0">
								<th class="min-w-100px">#</th>
								<th class="min-w-175px">{{__('messages.Name')}}</th>
								<th class="min-w-175px">{{__('messages.Image')}}</th>
								<th class="min-w-175px">{{__('messages.Status')}}</th>

								<th class=" min-w-100px">{{__("messages.Actions")}}</th>
							</tr>
						</thead>
						<tbody class="fw-semibold text-gray-600">
							@foreach($service->brands as $key=>$brand)
							<tr>
								<td data-kt-ecommerce-order-filter="order_id">
									<a href="{{route('admin.services.models',$brand->id)}}">#{{++$key}}</a>
								</td>

								<td class="">
									<span class="fw-bold">{{ session('lang') ?  $brand->name : $brand->name_ar}}</span>
								</td>

								<td><img src="{{asset($brand->image)}}" style="height:60px" alt=""></td>
								<td>
									<form action="{{route('admin.services.brand.update_status')}}" id="status-form" method="post">
										@csrf
										<input type="hidden" name="brand_id" value="{{$brand->id}}">
										@if($brand->status)
										<span  data-bs-title="{{__('messages.Click_to_Change_status')}}" onclick="document.getElementById('status-form').submit()"         data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="custom-tooltip" class="pointer badge badge-light-success">{{__('messages.Active')}}</span>
										@else
										<span data-bs-title="{{__('messages.Click_to_Change_status')}}" onclick="document.getElementById('status-form').submit()"         data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="custom-tooltip" class="pointer badge badge-light-danger">{{__('messages.Inactive')}}</span>
										@endif
									</form>
								</td>
								<td class="">
									<a class="btn btn-sm btn-light btn-flex btn-center btn-active-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Actions
										<i class="ki-duotone ki-down fs-5 ms-1"></i></a>
									<!--begin::Menu-->
									<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4" data-kt-menu="true">
										<div class="menu-item px-3">
											<a data-bs-toggle="modal"
												onclick="editData('{{$brand->id}}','{{$brand->name}}','{{$brand->name_ar}}','{{$brand->image}}')"
												data-bs-target="#kt_modal_edit_user" class="menu-link px-3">{{__("messages.Edit")}}</a>
										</div>
										<div class="menu-item px-3">
											<form action="{{route('admin.services.brand.delete',$brand->id)}}" id="delete-form" method="post">
												@csrf
												<button type="button" onclick="if(confirm('{{__('messages.Are_you_sure')}}')) document.getElementById('delete-form').submit()" class="menu-link px-3 btn " data-kt-ecommerce-order-filter="delete_row">{{__("messages.Delete")}}</button>
											</form>
										</div>
										<!--end::Menu item-->
									</div>
									<!--end::Menu-->
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

	<div class="modal fade" id="kt_modal_add_user" tabindex="-1" style="display: none;" aria-hidden="true">
		<!--begin::Modal dialog-->
		<div class="modal-dialog modal-dialog-centered mw-650px">
			<!--begin::Modal content-->
			<div class="modal-content">
				<!--begin::Modal header-->
				<div class="modal-header" id="kt_modal_add_user_header">
					<!--begin::Modal title-->
					<h2 class="fw-bold">{{__("messages.Add_car_type")}}</h2>

					<div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
						<span class="svg-icon svg-icon-1">
							<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
								<rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="currentColor"></rect>
								<rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="currentColor"></rect>
							</svg>
						</span>
					</div>
				</div>
				<div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
					<!--begin::Form-->
					<form id="kt_modal_add_user_form" class="form form-ajax fv-plugins-bootstrap5 fv-plugins-framework" action="{{route('admin.services.brand.store')}}" method="post" enctype="multipart/form-data">
						@csrf
						<input type="hidden" name="service_id" value="{{$service->id}}">
						<div class="d-flex flex-column scroll-y me-n7 pe-7" id="kt_modal_add_user_scroll" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_add_user_header" data-kt-scroll-wrappers="#kt_modal_add_user_scroll" data-kt-scroll-offset="300px" style="">
							<div class="fv-row mb-7">
								<label class="d-block fw-semibold fs-6 mb-5">{{__("messages.Image")}}</label>
								<div class="image-input image-input-outline image-input-placeholder" data-kt-image-input="true">
									<div class="image-input-wrapper w-125px h-125px" style="background-image: url({{asset('static/images/300-29.jpg')}});"></div>

									<label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" aria-label="Change avatar" data-bs-original-title="Change avatar" data-kt-initialized="1">
										<i class="bi bi-pencil-fill fs-7"></i>
										<input type="file" name="image" accept=".png, .jpg, .jpeg">
										<input type="hidden" name="image_remove">
									</label>

									<span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" aria-label="Cancel avatar" data-bs-original-title="Cancel avatar" data-kt-initialized="1">
										<i class="bi bi-x fs-2"></i>
									</span>
									<span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" aria-label="Remove avatar" data-bs-original-title="Remove avatar" data-kt-initialized="1">
										<i class="bi bi-x fs-2"></i>
									</span>
								</div>

								<div class="form-text">Allowed file types: png, jpg, jpeg.</div>
							</div>
							<div class="row">

								<div class="col-lg-6 col-sm-12 mb-2 fv-row mb-7 fv-plugins-icon-container">
									<label class="required fw-semibold fs-6 mb-2">{{__("messages.Name_en")}}</label>
									<input type="text" name="name" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="{{__('messages.Name_en')}}" value="" required="">
									<div class="fv-plugins-message-container invalid-feedback"></div>
								</div>
								<div class="col-lg-6 col-sm-12 mb-2 fv-row mb-7 fv-plugins-icon-container">
									<label class="required fw-semibold fs-6 mb-2">{{__("messages.Name_ar")}}</label>
									<input type="text" name="name_ar" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="{{__('messages.Name_ar')}}" value="" required="">
									<div class="fv-plugins-message-container invalid-feedback"></div>
								</div>

							</div>


						</div>
						<div class="text-center pt-15">
							<button type="reset" class="btn btn-light me-3" data-kt-users-modal-action="cancel">{{__("messages.Retreat")}}</button>
							<button type="submit" class="btn btn-primary" data-kt-users-modal-action="submit">
								<span class="indicator-label">{{__("messages.Save")}}</span>
								<span class="indicator-progress">Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
							</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="kt_modal_edit_user" tabindex="-1" style="display: none;" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered mw-650px">
			<div class="modal-content">
				<div class="modal-header" id="kt_modal_add_user_header">
					<h2 class="fw-bold">{{__("messages.Edit_car_type")}}</h2>

					<div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
						<span class="svg-icon svg-icon-1">
							<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
								<rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="currentColor"></rect>
								<rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="currentColor"></rect>
							</svg>
						</span>
					</div>
				</div>
				<div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
					<!--begin::Form-->
					<form id="kt_modal_add_user_form" class="form form-ajax fv-plugins-bootstrap5 fv-plugins-framework" action="{{route('admin.services.brand.update')}}" method="post" enctype="multipart/form-data">
						@csrf
						<input type="text" hidden name="brand_id" id="edit-brand-id">
						<div class="d-flex flex-column scroll-y me-n7 pe-7" id="kt_modal_add_user_scroll" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_add_user_header" data-kt-scroll-wrappers="#kt_modal_add_user_scroll" data-kt-scroll-offset="300px" style="">

							<div class="fv-row mb-7">
								<label class="d-block fw-semibold fs-6 mb-5">{{__("messages.Image")}}</label>
								<div class="image-input image-input-outline image-input-placeholder" data-kt-image-input="true">
									<div id="edit-image" class="image-input-wrapper w-125px h-125px" style="background-image: url({{asset('static/images/300-29.jpg')}});"></div>

									<label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" aria-label="Change avatar" data-bs-original-title="Change avatar" data-kt-initialized="1">
										<i class="bi bi-pencil-fill fs-7"></i>
										<input type="file" name="image" accept=".png, .jpg, .jpeg">
										<input type="hidden" name="image_remove">
									</label>

									<span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" aria-label="Cancel avatar" data-bs-original-title="Cancel avatar" data-kt-initialized="1">
										<i class="bi bi-x fs-2"></i>
									</span>
									<span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" aria-label="Remove avatar" data-bs-original-title="Remove avatar" data-kt-initialized="1">
										<i class="bi bi-x fs-2"></i>
									</span>
								</div>

								<div class="form-text">Allowed file types: png, jpg, jpeg.</div>
							</div>
							<div class="row">

								<div class="col-lg-6 col-sm-12 mb-2 fv-row mb-7 fv-plugins-icon-container">
									<label class="required fw-semibold fs-6 mb-2">{{__('messages.Name_en')}}</label>
									<input type="text" id="edit-name" name="name" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="{{__('messages.Name_en')}}" value="" required="">
									<div class="fv-plugins-message-container invalid-feedback"></div>
								</div>
								<div class="col-lg-6 col-sm-12 mb-2 fv-row mb-7 fv-plugins-icon-container">
									<label class="required fw-semibold fs-6 mb-2">{{__("messages.Name_ar")}}</label>
									<input type="text" id="edit-name-ar" name="name_ar" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="{{__('messages.Name_ar')}}" value="" required="">
									<div class="fv-plugins-message-container invalid-feedback"></div>
								</div>
							</div>


						</div>
						<div class="text-center pt-15">
							<button type="reset" class="btn btn-light me-3" data-kt-users-modal-action="cancel">{{__("messages.Retreat")}}</button>
							<button type="submit" class="btn btn-primary" data-kt-users-modal-action="submit">
								<span class="indicator-label">{{__("messages.Save")}}</span>
								<span class="indicator-progress">Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
							</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>


	@endsection
	@section("js")
	<script>
		function editData(id, name, name_ar,image) {
			document.getElementById("edit-brand-id").value = id;
			document.getElementById("edit-name").value = name;
			document.getElementById("edit-name-ar").value = name_ar;
			document.getElementById("edit-image").style.background=`url(${image})`
		}
	</script>
	@endsection