<div id="TwbsCarousel" class="carousel slide" data-ride="carousel">
    <ol class="carousel-indicators">
        <% loop $Images  %>
        <li data-target="#TwbsCarousel" data-slide-to="{$Pos(0)}" <% if $First %>class="active"<% end_if %>></li>
        <% end_loop %>
    </ol>
    <div class="carousel-inner" role="listbox">

       <% loop $Images %>
        <div class="carousel-item <% if $First %>active<% end_if %>">
            <img class="d-block img-fluid" src="{$Image.Fill(1000,600).URL}" alt="First slide">
        </div>
        <% end_loop %>
    </div>

</div>