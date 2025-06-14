@extends('admin.app')
@section('title',trans('messages.Title'))
@section('content')
<div class="d-flex flex-column flex-column-fluid">
	<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
		<div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
			<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
				<h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">{{__('messages.Title')}}</h1>
				<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
					<li class="breadcrumb-item text-muted">
						<a class="text-muted text-hover-primary">{{__('messages.Section')}}</a>
					</li>
					<li class="breadcrumb-item">
						<span class="bullet bg-gray-400 w-5px h-2px"></span>
					</li>
					<li class="breadcrumb-item text-muted">{{__('messages.Title')}}</li>
				</ul>
			</div>
			<div class="d-flex align-items-center gap-2 gap-lg-3">
				<div class="m-0">
					<a href="#" class="btn btn-sm btn-flex btn-secondary fw-bold" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
						<i class="ki-duotone ki-filter fs-6 text-muted me-1">
							<span class="path1"></span>
							<span class="path2"></span>
						</i>{{__('messages.Filter')}}</a>
					<div class="menu menu-sub menu-sub-dropdown w-250px w-md-300px" data-kt-menu="true" id="kt_menu_64b77630f13b9">
						<div class="px-7 py-5">
							<div class="fs-5 text-dark fw-bold">{{__('messages.Filter_Options')}}</div>
						</div>

						<div class="separator border-gray-200"></div>

						<div class="px-7 py-5">
							<div class="mb-10">
								<label class="form-label fw-semibold">{{__('messages.Status')}}:</label>

								<div>
									<select class="form-select form-select-solid" multiple="multiple" data-kt-select2="true" data-close-on-select="false" data-placeholder="Select option" data-dropdown-parent="#kt_menu_64b77630f13b9" data-allow-clear="true">
										<option></option>
										<option value="1">Approved</option>
										<option value="2">Pending</option>
										<option value="2">In Process</option>
										<option value="2">Rejected</option>
									</select>
								</div>
							</div>



							<div class="d-flex justify-content-end">
								<button type="reset" class="btn btn-sm btn-light btn-active-light-primary me-2" data-kt-menu-dismiss="true">{{__('messages.Reset')}}</button>
								<button type="submit" class="btn btn-sm btn-primary" data-kt-menu-dismiss="true">{{__('messages.Apply')}}</button>
							</div>
						</div>
					</div>
				</div>

				<a href="#" class="btn btn-sm fw-bold btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_create_app">{{__('messages.Create')}}</a>
			</div>
		</div>
	</div>
	<div id="kt_app_content_container" class="app-container  container-xxl ">
		<!--begin::Layout-->
		<div class="d-flex flex-column flex-xl-row">
			<!--begin::Sidebar-->
			<div class="flex-column flex-lg-row-auto w-100  mb-10">

				<!--begin::Card-->
				<div class="card mb-5 mb-xl-8">
					<!--begin::Card body-->
					<div class="card-body pt-15">
	
					</div>
					<!--end::Card body-->
				</div>
				<!--end::Card-->

			</div>
			<!--end::Sidebar-->
		</div>
		<!--end::Layout-->

		<!--begin::Modals-->

	</div>

	@endsection