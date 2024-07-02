@extends('layout.admin.app')

@section('admin.app-content')
   <div class="teachers-page">
      <div class="row mb-4">
         <div class="col-sm-6 text-start">
            <a href="{{ url()->previous() }}" class="raw-btn raw-back-btn back-btn">
               <i class="bi bi-arrow-left"></i> <span>Back</span>
            </a>
         </div>
         <div class="col-sm-6 text-end"></div>
      </div>

      <div class="row justify-content-center">
         <div class="col-md-12">
            <form method="post" action="{{ route('admin.challenges.update', $challenge->id) }}">
               @csrf
               @method('put')
               <div class="card text-bg-dark">
                  <div class="card-header">
                     <div class="row gy-3">
                        <div class="col-sm-4 text-start">
                           <h4 class="mb-0 py-2">Edit Challenge</h4>
                        </div>
                     </div>
                  </div>
                  <div class="card-body">
                     <div class="row gy-3 mb-24">
                        {{--  <div class="col-sm-4">
                           <label for="service_id" class="ts-label"><span class="text-danger required">*</span>
                              <span>Service Name</span>
                           </label>
                           <select name="service_id" id="service_id" class="select-control" style="width: 100%;" required>
                              <option value="">Select Service</option>
                              @foreach ($services as $service)
                                 <option value="{{ $service->id }}" data-id="{{ $service->id }}"
                                    {{ old('service_id', $challenge->service_id) == $service->id ? 'selected' : '' }}>
                                    {{ $service->name }}
                                 </option>
                              @endforeach
                           </select>
                           @if ($errors->has('service_id'))
                              <div class="error">{{ $errors->first('service_id') }}</div>
                           @endif
                        </div>  --}}

                        <div class="col-sm-3">
                           <label for="challenge_title" class="ts-label"><span class="text-danger required">*</span>
                              <span>Challege Title</span>
                           </label>

                           <input type="text" class="form-control input-control" name="challenge_title"
                              id="challenge_title" placeholder="Challenge Title"
                              value="{{ old('challenge_title', $challenge->title) }}">
                           @if ($errors->has('challenge_title'))
                              <div class="error">{{ $errors->first('challenge_title') }}</div>
                           @endif
                        </div>

                        {{--  <div class="col-sm-4">
                           <label for="buying_power" class="ts-label"><span class="text-danger required">*</span>
                              <span>Buying Power</span>
                           </label>
                           <input class="form-control input-control" type="number" name="buying_power"
                              placeholder="Buying Power" autocomplete="off"
                              value="{{ old('buying_power', $challenge->buying_power) }}">
                           @if ($errors->has('buying_power'))
                              <div class="error">{{ $errors->first('buying_power') }}</div>
                           @endif
                        </div>  --}}

                        <div class="col-sm-3">
                           <label for="challenge_price" class="ts-label">
                              <span>Challenge Price</span>
                           </label>
                           <input class="form-control input-control mt-1" type="number" name="challenge_price"
                              placeholder="Challege Price" autocomplete="off" id="challenge_price"
                              value="{{ old('challenge_price', $challenge->price) }}">
                           @if ($errors->has('challenge_price'))
                              <div class="error">{{ $errors->first('challenge_price') }}</div>
                           @endif
                        </div>

                        <div class="col-sm-3">
                           <label for="service_id" class="ts-label"><span class="text-danger required">*</span>
                              <span>Market Data</span>
                           </label>

                           <select name="market_data_id[]" id="market_data_id" multiple="multiple" class="select-control select2" style="width: 100%;" required>
                              <option value="">Select Market Data</option>
                              @foreach ($market_data as $data)
                                 @if ($challenge->market_data_id == $data->id)
                                    <option value="{{ $data->id }}" selected>{{ $data->name }}-oldarray</option>
                                 @elseif((in_array($data->id, (array)$compareMarket)))
                                    <option value="{{ $data->id }}" selected>{{ $data->name }}-inarray</option>
                                 @else
                                    <option value="{{ $data->id }}">{{ $data->name }}-last</option>
                                 @endif
                              @endforeach
                           </select>
                           @if ($errors->has('service_id'))
                              <div class="error">{{ $errors->first('service_id') }}</div>
                           @endif
                        </div>

                        <div class="col-sm-3">
                           <label for="is_feature" class="ts-label mt-4">
                              <input class="form-check-input" type="checkbox" name="is_feature" id="is_feature"
                                 value="1" {{ old('is_feature', $challenge->is_feature) === 1 ? 'checked' : null }}>
                              <span>Is
                                 Feature</span>
                           </label>
                           @if ($errors->has('is_feature'))
                              <div class="error">{{ $errors->first('is_feature') }}</div>
                           @endif
                        </div>

                     </div>


                     {{--  <div class="row gy-3 mb-24">
                        <div class="col-sm-6">
                           <label for="service_id" class="ts-label"><span class="text-danger required">*</span>
                              <span>Market Data</span>
                           </label>
                           <select name="market_data_id" id="market_data_id" class="select-control" style="width: 100%;" required>
                              <option value="">Select Market Data</option>
                              @foreach ($market_data as $data)
                                 <option value="{{ $data->id }}"
                                    {{ old('market_data_id', $challenge->market_data_id) == $data->id ? 'selected' : '' }}>
                                    {{ $data->name }}
                                 </option>
                              @endforeach
                           </select>
                           @if ($errors->has('service_id'))
                              <div class="error">{{ $errors->first('service_id') }}</div>
                           @endif
                        </div>
                        <div class="col-sm-6">
                           <label for="clone_id" class="ts-label"><span class="text-danger required">*</span>
                              <span>Clone ID</span>
                           </label>

                           <input type="text" class="form-control input-control" name="clone_id"
                              id="clone_id" placeholder="Clone Id"
                              value="{{ old('clone_id', $challenge->clone_id) }}">
                           @if ($errors->has('clone_id'))
                              <div class="error">{{ $errors->first('clone_id') }}</div>
                           @endif
                        </div>
                     </div>  --}}

                     {{--  <div class="row gy-3 mb-24">
                        <div class="col-sm-6">
                           <label for="stripe_product_id" class="ts-label">
                              <span>Stripe Product Id</span>
                           </label>
                           <input class="form-control input-control" type="text" name="stripe_product_id"
                              placeholder="Stripe Product ID"
                              value="{{ old('stripe_product_id', $challenge->challengeStripe->stripe_product_id) }}">
                        </div>
                        <div class="col-sm-6">
                           <label for="stripe_product_price_id" class="ts-label">
                              <span>Stripe Product Price Id</span>
                           </label>
                           <input class="form-control input-control" type="text" name="stripe_product_price_id"
                              placeholder="Stripe Product Price ID"
                              value="{{ old('stripe_product_price_id', $challenge->challengeStripe->stripe_product_price_id) }}">
                        </div>
                     </div>
                     <div class="select-wrapper">
                        @foreach ($challenge->cardHead as $ch)
                           <div class="row gy-3 mb-24">
                              <div class="col-sm-6">
                                 <label for="card_head_title_id" class="ts-label">
                                    <span>Card Head Title</span>
                                 </label>
                                 <select name="card_head_title_id[]" id="card_head_title_id"
                                    class="select-control card_head_title_id" style="width: 100%;" required>
                                    <option value="">Select Card Head Title</option>
                                    @foreach ($head_titles as $head_title)
                                       <option value="{{ $head_title->id }}" data-id="{{ $head_title->id }}"
                                          {{ old('card_head_title_id', optional($ch->cardHeadTitle)->id) == $head_title->id ? 'selected' : '' }}>
                                          {{ $head_title->title }}
                                       </option>
                                    @endforeach
                                 </select>
                              </div>
                              <div class="col-sm-5">
                                 <label for="card_sub_head_title_id" class="ts-label">
                                    <span>Card Sub Head Title</span>
                                 </label>
                                 <select name="card_sub_head_title_id[{{ $loop->index }}][]" id="card_sub_head_title_id-{{$loop->index}}"
                                    class="select-control card_sub_head_title_id" style="width: 100%;" multiple data-index="{{ $loop->index }}">
                                    <option value="">Select Card Sub Head Title</option>
                                    @foreach ($ch->cardHeadSubTitle as $sub_title)
                                       <option value="{{ optional($sub_title->cardSubHeadTitle)->id }}"
                                          {{ old('card_sub_head_title_id', optional($sub_title->cardSubHeadTitle)->id) ? 'selected' : '' }}>
                                          {{ optional($sub_title->cardSubHeadTitle)->title . ' ' . optional($sub_title->cardSubHeadTitle)->value }}
                                       </option>
                                    @endforeach
                                 </select>
                              </div>
                              <div class="col-sm-1 text-end">
                                 @if ($loop->first)
                                    <button class="btn btn-primary add-more mt-4" type="button">
                                       <i class="bi bi-plus"></i>
                                    </button>
                                 @else
                                    <button class="btn btn-danger remove-row mt-4" type="button">
                                       <i class="bi bi-dash"></i>
                                    </button>
                                 @endif
                              </div>
                           </div>
                        @endforeach
                     </div>  --}}
                  </div>
                  <div class="card-footer">
                     <div class="row gy-3 justify-content-end py-2">
                        <div class="col-sm-4">
                           <input class="btn-teacher-submit btn-submit w-100" type="submit" value="Update Challege">
                        </div>
                     </div>
                  </div>
               </div>
            </form>
         </div>
      </div>
   </div>

   @push('scripts')
      <script>
         @if ($errors->get('errors'))
            Swal.fire({
               title: 'Error',
               icon: 'warning',
               text: "There is Something Wrong!",
            });
         @endif
         $(document).ready(function() {
            $("#market_data_id").select2();
            function initializeSelect2(selectEle) {
               selectEle.select2();
            }

            let indexs = [];
            $('.card_sub_head_title_id').each(function() {
               initializeSelect2($(this));
               indexs.push($(this).data("index"));
            });
            console.log(indexs)


            var i = indexs.pop() + 1;
            console.log(i);

            $('.add-more').on('click', function(e) {
               let head_title_options = "";
               @foreach ($head_titles as $title)
                  head_title_options += '<option value="{{ $title->id }}">' + "{{ $title->title }}" +
                     '</option>';
               @endforeach

               let fields = '<div class="row gy-3 mb-24" ><div class="col-sm-6">';
               fields += '<label for="card_head_title_id" class="ts-label"><span>Card Head Title</span></label>';
               fields +=
                  '<select name="card_head_title_id[]" id="card_head_title_id" class="select-control card_head_title_id" style="width: 100%;" required>';
               fields += '<option value="">Select Card Head Title</option>';
               fields += head_title_options;
               fields += '</select>';
               fields += '</div>';
               fields += '<div class="col-sm-5">';
               fields +=
                  '<label for="card_sub_head_title_id" class="ts-label"><span>Card Sub Head Title</span></label>';
               fields +=
                  '<select name="card_sub_head_title_id[' + i++ + '][]" id="card_sub_head_title_id-' + i++ +
                  '" class="select-control card_sub_head_title_id" style="width: 100%;" multiple>';
               fields += '<option value="">Select Card Sub Head Title</option>';
               fields += '</select>';
               fields += '</div>';
               fields += '<div class="col-sm-1 text-end">';
               fields +=
                  '<button class="btn btn-danger mt-4 remove-row" type="button"><i class="bi bi-dash"></i></button>';
               fields += '</div></div>';
               $(".select-wrapper").append(fields);

               $('.card_sub_head_title_id').each(function() {
                  initializeSelect2($(this));
               });
            });

            $(document).on('click', '.remove-row', function(e) {
               e.preventDefault();
               console.log($(this).parent().parent());
               $(this).parent().parent().remove();
            });

            $(document).on('change', '.card_head_title_id', function(e) {
               const parentEl = $(this).parent().siblings('.col-sm-5').find("select");

               const title_id = $(this).val();
               const route = "{{ route('admin.get_sub_titles', 'id') }}"
               const url = route.replace("id", title_id);

               var request = $.ajax({
                  url: url,
                  type: "get",
                  data: {
                     title_id: title_id,
                  },
               });

               request.done(function(response) {
                  console.log(response);
                  var fields = '<option value="">Select Card Sub Head Title</option>'

                  response.forEach(res => {
                     fields += "<option value=" + res.id + ">" + res.title + " " + res.value +
                        "</option>";
                  });

                  parentEl.html(fields);
               });
            })
         });
      </script>
   @endpush
@endsection
