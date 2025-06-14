<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha.6/css/bootstrap.css" />



<div style="display: flex; justify-content:center;" class="my-2">
    <div class="orange " style="height: 20px; background:orange;width: 20px; margin:0 5px 0 5px"></div>
    {{__("messages.Available")}}    

    <div class="red "  style="height: 20px; background:red;width: 20px;  margin:0 5px 0 5px"></div>
    {{__("messages.Not_Available")}}
    <div class="green "  style="height: 20px; background:green;width: 20px;  margin:0 5px 0 5px"></div>
    {{__("messages.Booked")}}
    <div class="black "  style="height: 20px; background:black;width: 20px;  margin:0 5px 0 5px"></div>
    {{__("messages.Event_day")}}
</div>
<div id="calendar" class="card-body"></div>



<div class="modal fade" id="selectDay" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">{{__('messages.Event_days')}}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="text" id="title">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('messages.Close')}}</button>
                <button type="button" id="save-changes" class="btn btn-primary">{{__('messages.Save_changes')}}</button>
            </div>
        </div>
    </div>
</div>
