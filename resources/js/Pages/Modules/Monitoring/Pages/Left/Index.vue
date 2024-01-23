<template>
    <div class="p-4 d-flex flex-column h-100" style="overflow: auto;">
        <h6 class="fs-11 text-muted text-uppercase mb-3 mt-0 fw-bold">ONGOING SCHOLARS</h6>
        <div class="d-flex align-items-center">
            <div class="flex-shrink-0">
                <i class="ri-database-2-line fs-17"></i>
            </div>
            <div class="flex-grow-1 ms-3 overflow-hidden">
                <b-progress class="animated-progress progress-sm mb-2" :max="latest.ongoing">
                    <b-progress-bar :value="latest.received" variant="primary" />
                </b-progress>
                <span class="text-muted fs-12 d-block text-truncate"><b>{{latest.received}}</b> out of <b>{{latest.ongoing}}</b> ongoing scholars received benefits.</span>
            </div>
        </div>
        <hr class="text-muted"/>
     </div>
</template>
<script>
export default {
    
}
</script>