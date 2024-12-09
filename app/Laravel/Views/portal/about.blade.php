@extends('portal._layouts.web')

@section('content')
<div class="section-header">
    <h1>About</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="#">About</a></div>
        <div class="breadcrumb-item">Data</div>
    </div>
</div>
<div class="hero text-white hero-bg-image hero-bg-parallax mb-4" style="background-image: url('{{asset('assets/img/rikdo.png')}}');">
    <div class="hero-inner">
        <h2>About Us</h2>
        <p class="lead">Dedicated to knowledge-sharing by providing a collaborative platform for researchers.</p>
    </div>
</div>
<div class="section-body">
    <div class="card">
        <div class="card-header">
            <h4>About Description</h4>
        </div>
        <div class="card-body">
            <p class="text-justify">
                The Research and Innovation Knowledge Development Office (RIKDO) plays a pivotal role in fostering a thriving research culture within the College. It serves as the cornerstone of the institution's efforts to advance research initiatives by offering strategic leadership that aligns with the College’s goals and vision. Through its guidance, RIKDO ensures that the institution remains at the forefront of research excellence.<br>
                A key function of RIKDO is to provide clear policy direction that shapes the research agenda. By setting standards and priorities, it creates a structured environment where researchers can effectively contribute to the academic and practical needs of stakeholders. These policies act as a roadmap, ensuring that all research efforts are meaningful, impactful, and aligned with institutional objectives.<br>
                Furthermore, RIKDO creates avenues and opportunities for research development. It bridges the gap between researchers and stakeholders by identifying and addressing specific research needs. Through these initiatives, RIKDO not only supports the professional growth of researchers but also ensures that the outcomes of their work have a lasting and meaningful impact on the community.
            </p>
        </div>
    </div>
</div>
@stop