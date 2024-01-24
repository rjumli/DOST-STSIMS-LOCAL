<template>
    <h6 class="fs-11 text-muted text-uppercase mb-3 mt-0">ONGOING SCHOLARS</h6>
    <div class="d-flex align-items-center">
        <div class="flex-shrink-0">
            <i class="ri-team-fill fs-17"></i>
        </div>
        <div class="flex-grow-1 ms-3 overflow-hidden">
            <b-progress class="animated-progress progress-sm mb-2" :max="left.total">
                <b-progress-bar :value="left.ongoing" variant="dark" />
            </b-progress>
            <span class="text-muted fs-12 d-block text-truncate"><b>{{left.ongoing}}</b> out of <b>{{left.total}}</b> are ongoing scholars.</span>
        </div>
    </div>
    <hr class="text-muted"/>
    <h6 class="fs-11 text-muted text-uppercase mb-3 mt-0">Scholars Enrolled</h6>
    <div class="d-flex align-items-center">
        <div class="flex-shrink-0">
            <i class="ri-group-2-fill fs-17"></i>
        </div>
        <div class="flex-grow-1 ms-3 overflow-hidden">
            <b-progress class="animated-progress progress-sm mb-2" :max="left.ongoing">
                <b-progress-bar :value="left.enrolled" variant="dark" />
            </b-progress>
            <span class="text-muted fs-12 d-block text-truncate"><b>{{left.enrolled}}</b> out of <b>{{left.ongoing}}</b> ongoing scholars are enrolled.</span>
        </div>
    </div>
    <hr class="text-muted"/>
    <h6 class="fs-11 text-muted text-uppercase mb-3 mt-0">Schools Semester</h6>
    <div class="d-flex align-items-center">
        <div class="flex-shrink-0">
            <i class="ri-hotel-line fs-17"></i>
        </div>
        <div class="flex-grow-1 ms-3 overflow-hidden">
            <b-progress class="animated-progress progress-sm mb-2" :max="left.schools">
                <b-progress-bar :value="left.schools_active" variant="dark" />
            </b-progress>
            <span class="text-muted fs-12 d-block text-truncate"><b>{{left.schools_active}}</b> out of <b>{{left.schools}}</b> schools with active semester.</span>
        </div>
    </div>
    <hr class="text-muted"/>
    <Lists :lists="left.monitoring"/>
</template>
<script>
import Lists from './Lists.vue';
export default {
    components: { Lists },
    props: ['left']
}
</script>