<h4 class="mb-3">{{ __('messages.Personal_Information') }}</h4>
@if (isset($driver) && $driver)
    <div class="row">
        <div class="col-12 mb-3">
            <label class="form-label">{{ __('messages.full_name') }}</label>
            <input required type="text" class="form-control" name="name"
                value="{{ old('f_name', $driver->name ?? '') }}" pattern="[A-Za-z\s]+" title="Only letters are allowed">
        </div>

    </div>
@else
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">{{ __('messages.first_name') }}</label>
            <input required type="text" class="form-control" name="f_name"
                value="{{ old('f_name', $driver->f_name ?? '') }}" pattern="[A-Za-z\s]+"
                title="Only letters are allowed">
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">{{ __('messages.last_name') }}</label>
            <input required type="text" class="form-control" name="l_name"
                value="{{ old('l_name', $driver->l_name ?? '') }}" pattern="[A-Za-z\s]+"
                title="Only letters are allowed">
        </div>
    </div>
@endif
<div class="row">
    <div class="col-12 mb-3">
        <label class="form-label">{{ __('messages.Email') }}</label>
        <input required type="email" class="form-control" name="email"
            value="{{ old('email', $driver->email ?? '') }}">
    </div>
</div>
<div class="row mt-3">
    <div class="col-md-6 mb-3" style="display: flex; gap: 10px;">
        <select id="country_code" name="country_code" style="width:140px" class="form-select">
            <option disabled>{{ __('messages.Choose_country') }}</option>
            <option value="1">🇺🇸 United States (+1)</option>
            <option value="44">🇬🇧 United Kingdom (+44)</option>
            <option value="20" selected>🇪🇬 Egypt (+20)</option>
            <option value="971">🇦🇪 United Arab Emirates (+971)</option>
            <option value="966">🇸🇦 Saudi Arabia (+966)</option>
            <option value="974">🇶🇦 Qatar (+974)</option>
            <option value="965">🇰🇼 Kuwait (+965)</option>
            <option value="973">🇧🇭 Bahrain (+973)</option>
            <option value="968">🇴🇲 Oman (+968)</option>
            <option value="961">🇱🇧 Lebanon (+961)</option>
            <option value="962">🇯🇴 Jordan (+962)</option>
            <option value="963">🇸🇾 Syria (+963)</option>
            <option value="964">🇮🇶 Iraq (+964)</option>
            <option value="212">🇲🇦 Morocco (+212)</option>
            <option value="213">🇩🇿 Algeria (+213)</option>
            <option value="216">🇹🇳 Tunisia (+216)</option>
            <option value="218">🇱🇾 Libya (+218)</option>
            <option value="249">🇸🇩 Sudan (+249)</option>
            <option value="7">🇷🇺 Russia (+7)</option>
            <option value="86">🇨🇳 China (+86)</option>
            <option value="91">🇮🇳 India (+91)</option>
            <option value="92">🇵🇰 Pakistan (+92)</option>
            <option value="880">🇧🇩 Bangladesh (+880)</option>
            <option value="81">🇯🇵 Japan (+81)</option>
            <option value="82">🇰🇷 South Korea (+82)</option>
            <option value="90">🇹🇷 Turkey (+90)</option>
            <option value="39">🇮🇹 Italy (+39)</option>
            <option value="33">🇫🇷 France (+33)</option>
            <option value="34">🇪🇸 Spain (+34)</option>
            <option value="49">🇩🇪 Germany (+49)</option>
            <option value="43">🇦🇹 Austria (+43)</option>
            <option value="41">🇨🇭 Switzerland (+41)</option>
            <option value="31">🇳🇱 Netherlands (+31)</option>
            <option value="32">🇧🇪 Belgium (+32)</option>
            <option value="46">🇸🇪 Sweden (+46)</option>
            <option value="45">🇩🇰 Denmark (+45)</option>
            <option value="47">🇳🇴 Norway (+47)</option>
            <option value="48">🇵🇱 Poland (+48)</option>
            <option value="380">🇺🇦 Ukraine (+380)</option>
            <option value="357">🇨🇾 Cyprus (+357)</option>
            <option value="61">🇦🇺 Australia (+61)</option>
            <option value="64">🇳🇿 New Zealand (+64)</option>
            <option value="1">🇨🇦 Canada (+1)</option>
            <option value="52">🇲🇽 Mexico (+52)</option>
            <option value="55">🇧🇷 Brazil (+55)</option>
            <option value="54">🇦🇷 Argentina (+54)</option>
            <option value="51">🇵🇪 Peru (+51)</option>
            <option value="56">🇨🇱 Chile (+56)</option>
            <option value="63">🇵🇭 Philippines (+63)</option>
            <option value="60">🇲🇾 Malaysia (+60)</option>
            <option value="65">🇸🇬 Singapore (+65)</option>
            <option value="66">🇹🇭 Thailand (+66)</option>
            <option value="84">🇻🇳 Vietnam (+84)</option>
            <option value="62">🇮🇩 Indonesia (+62)</option>
            <option value="94">🇱🇰 Sri Lanka (+94)</option>
            <option value="880">🇧🇩 Bangladesh (+880)</option>
            <option value="254">🇰🇪 Kenya (+254)</option>
            <option value="27">🇿🇦 South Africa (+27)</option>
            <option value="234">🇳🇬 Nigeria (+234)</option>
            <option value="221">🇸🇳 Senegal (+221)</option>
            <option value="225">🇨🇮 Côte d'Ivoire (+225)</option>
            <option value="256">🇺🇬 Uganda (+256)</option>
            <option value="255">🇹🇿 Tanzania (+255)</option>
        </select>
        <input required type="text" class="form-control" name="phone"
            value="{{ old('phone', $driver->phone ?? '') }}" data-fake=""
            title="Only numbers are allowed *123456789" maxlength="11" pattern="^[1-9][0-9]{0,11}$">
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">{{ __('messages.password') }}</label>
        <input {{ isset($driver) ? '' : 'required' }} type="password" class="form-control" name="password"
            data-fake="12345678">
    </div>
</div>
<div class="mt-3">
    <label class="form-label">{{ __('messages.address') }}</label>
    <input required type="text" class="form-control" name="address"
        value="{{ old('address', $driver->address ?? '') }}" data-fake="Cairo">
</div>
<div class="row mt-3">
    <div class="col-md-6 mb-3">
        <label class="form-label">{{ __('messages.date_of_birth') }}</label>
        <input required type="date" class="form-control" name="date_of_birth"
            value="{{ old('date_of_birth', $driver->date_of_birth ?? '') }}" data-fake="1989-12-11">
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">{{ __('messages.gender') }}</label>
        <select required class="form-control" name="gender">
            <option value="male" {{ old('gender', $driver->gender ?? '') == 'male' ? 'selected' : '' }}>
                {{ __('messages.male') }}</option>
            <option value="female" {{ old('gender', $driver->gender ?? '') == 'female' ? 'selected' : '' }}>
                {{ __('messages.female') }}</option>
        </select>
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">{{ __('messages.account_photo') }}</label>
        <input {{ isset($driver) ? '' : 'required' }} type="file" class="form-control" name="account_photo">

        @php
            $licenseFront = isset($driver) ? $driver->documents()->where('name', 'account_photo')->first() : null;
        @endphp

        @if ($licenseFront)
            <img src="{{ asset($licenseFront->attachment) }}" alt="" class="img-thumbnail mt-2"
                style="max-width: 200px;">
        @endif
    </div>
</div>

<h4 class="mt-5 mb-3">{{ __('messages.Official_Documents') }}</h4>
<div class="row">
    <div class="col-md-12 my-3">
        <label class="form-label">{{ __('messages.id_number') }}</label>
        <input required type="text" class="form-control" name="id_number"
            value="{{ old('id_number', $driver->id_number ?? '') }}" data-fake="30303030454545" pattern="\d*"
            title="Only numbers are allowed">
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">{{ __('messages.id_front') }}</label>
        <input {{ isset($driver) ? '' : 'required' }} type="file" class="form-control" name="id_front">
        @if (isset($driver))
            <img src="{{ asset($driver->documents()->where('name', 'id_front')->first()->attachment) }}"
                style="height:100px" alt="">
        @endif
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">{{ __('messages.id_back') }}</label>
        <input {{ isset($driver) ? '' : 'required' }} type="file" class="form-control" name="id_back">
        @if (isset($driver))
            <img src="{{ asset($driver->documents()->where('name', 'id_back')->first()->attachment) }}"
                style="height:100px" alt="">
        @endif
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">{{ __('messages.criminal_record') }}</label>
        <input {{ isset($driver) ? '' : 'required' }} type="file" class="form-control" name="criminal_record">
        @if (isset($driver))
            <img src="{{ asset($driver->documents()->where('name', 'criminal_record')->first()->attachment) }}"
                style="height:100px" alt="">
        @endif
    </div>
</div>

<h4 class="mt-5 mb-3">{{ __('messages.Licenses') }}</h4>
<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">{{ __('messages.issue_date') }}</label>
        <input required type="date" class="form-control" name="driving_license_issue_date"
            value="{{ old('driving_license_issue_date', $driver->driving_license_issue_date ?? '') }}"
            data-fake="2020-12-01">
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">{{ __('messages.expiry_date') }}</label>
        <input required type="date" class="form-control" name="driving_license_expiry_date"
            value="{{ old('driving_license_expiry_date', $driver->driving_license_expiry_date ?? '') }}"
            data-fake="2026-12-01">
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">{{ __('messages.driving_license_front') }}</label>
        <input {{ isset($driver) ? '' : 'required' }} type="file" class="form-control"
            name="driving_license_front">
        @if (isset($driver))
            <img src="{{ asset($driver->documents()->where('name', 'driving_license_front')->first()->attachment) }}"
                style="height:100px" alt="">
        @endif
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">{{ __('messages.driving_license_back') }}</label>
        <input {{ isset($driver) ? '' : 'required' }} type="file" class="form-control"
            name="driving_license_back">
        @if (isset($driver))
            <img src="{{ asset($driver->documents()->where('name', 'driving_license_back')->first()->attachment) }}"
                style="height:100px" alt="">
        @endif
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">{{ __('messages.photo_with_driving_license') }}</label>
        <input {{ isset($driver) ? '' : 'required' }} type="file" class="form-control"
            name="photo_with_driving_license">
        @if (isset($driver))
            <img src="{{ asset($driver->documents()->where('name', 'photo_with_driving_license')->first()->attachment) }}"
                style="height:100px" alt="">
        @endif
    </div>
</div>

<h4 class="mt-5 mb-3">{{ __('messages.Vehicle_Information') }}</h4>
<div class="row">
    <div class="col-md-4 mb-3">
        <label for="carType" class="form-label">@lang('messages.Car_Type')</label>
        <select required name="car_type" id="carType" class="form-select">
            <option value="">@lang('messages.Select_Car_Type')</option>
            @if (!empty($car_types))
                @foreach ($car_types as $type)
                    <option value="{{ $type->id }}" {{ $driver->car_type == $type->id ? 'selected' : '' }}>
                        {{ session('lang') == 'ar' ? $type->name_ar : $type->name }}
                    </option>
                @endforeach
            @endif
        </select>
    </div>
    <div class="col-md-4 mb-3">
        <label for="brand" class="form-label">@lang('messages.Brand')</label>
        <select required name="brand_id" id="brand" class="form-select" {{ isset($driver) ? '' : 'disabled' }}>
            <option value="">@lang('messages.Select_Brand')</option>
            @if (!empty($brands))
                @foreach ($brands as $brand)
                    <option value="{{ $brand->id }}" {{ $driver->brand_id == $brand->id ? 'selected' : '' }}>
                        {{ session('lang') == 'ar' ? $brand->name_ar : $brand->name }}
                    </option>
                @endforeach
            @endif
        </select>
    </div>
    <div class="col-md-4 mb-3">
        <label for="model" class="form-label">@lang('messages.Model')</label>
        <select required name="model_id" id="model" class="form-select" {{ isset($driver) ? '' : 'disabled' }}>
            <option value="">@lang('messages.Select_Model')</option>
            @if (!empty($models))
                @foreach ($models as $model)
                    <option value="{{ $model->id }}" {{ $driver->model_id == $model->id ? 'selected' : '' }}>
                        {{ session('lang') == 'ar' ? $model->name_ar : $model->name }}
                    </option>
                @endforeach
            @endif
        </select>
    </div>
</div>
<div class="row mt-3">
    <div class="col-md-4 mb-3">
        <label class="form-label">{{ __('messages.car_color') }}</label>
        <input required type="text" class="form-control" name="car_color"
            value="{{ old('car_color', $driver->car_color ?? '') }}" data-fake="black">

    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">{{ __('messages.car_plate_number') }}</label>
        <input required type="text" class="form-control" name="car_plate_number"
            value="{{ old('car_plate_number', $driver->car_plate_number ?? '') }}" data-fake="123456">
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">{{ __('messages.production_year') }}</label>
        <input required type="text" class="form-control" name="production_year"
            value="{{ old('production_year', $driver->production_year ?? '') }}" data-fake="2020">
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">{{ __('messages.license_front') }}</label>
        <input {{ isset($driver) ? '' : 'required' }} type="file" class="form-control" name="license_front">
        @if (isset($driver))
            <img src="{{ asset($driver->documents()->where('name', 'license_front')->first()->attachment) }}"
                style="height:100px" alt="">
        @endif
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">{{ __('messages.license_back') }}</label>
        <input {{ isset($driver) ? '' : 'required' }} type="file" class="form-control" name="license_back">
        @if (isset($driver))
            <img src="{{ asset($driver->documents()->where('name', 'license_back')->first()->attachment) }}"
                style="height:100px" alt="">
        @endif
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">{{ __('messages.car_photo') }}</label>
        <input {{ isset($driver) ? '' : 'required' }} type="file" class="form-control" name="car_photo">
        @if (isset($driver))
            <img src="{{ asset($driver->documents()->where('name', 'car_photo')->first()->attachment) }}"
                style="height:100px" alt="">
        @endif
    </div>
</div>
