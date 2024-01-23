<template>
    <b-row class="align-items-center" style="overflow: auto;">
        <b-col cols="6">
            <h6 class="text-primary text-uppercase fw-semibold text-truncate fs-12 mb-3">Ongoing Scholars</h6>
            <h4 class="fs- mb-0">{{totalSum}}</h4>
            <p class="mb-0 mt-2 text-muted">
                <span class="badge bg-success-subtle text-success">15.72 %</span>
                from previous year</p>
        </b-col>
        <b-col cols="6">
            <div class="text-center">
                <img src="/imagess/illustrator-1.png" class="img-fluid" alt="">
            </div>
        </b-col>
        <b-col cols="12">
            <div class="mt-2 pt-2">
                <b-progress class="progress-lg rounded-pill" :max="totalSum">
                    <b-progress-bar  v-for="(list,index) in modifiedItems" v-bind:key="index" :value="list.status_count" :variant="list.color" class="rounded-0" />
                </b-progress>
            </div>
        </b-col>
        <b-col cols="12">
            <div class="mt-2 pt-2">
                <div class="d-flex mb-2" v-for="(list,index) in modifiedItems" v-bind:key="index">
                    <div class="flex-grow-1">
                        <p class="text-truncate text-muted fs-14 mb-0">
                            <i class="mdi mdi-circle align-middle me-2" :class="'text-'+list.color"></i>{{list.name}}
                        </p>
                    </div>
                    <div class="flex-shrink-0">
                        <p class="mb-0">{{list.status_count}}</p>
                    </div>
                </div>
            </div>
        </b-col>
    </b-row>
   
    <hr class="text-muted mb-3"/>
</template>
<script>
export default {
    props:['statuses'],
    computed: {
        totalSum() {
            let a = (this.statuses.statuses) ? this.statuses.statuses.reduce((sum, item) => sum + parseFloat(item.status_count), 0) : 0;
            return a;
        },
        modifiedItems() {
            let a = (this.statuses.statuses) ?  this.statuses.statuses.map(item => ({...item, color: item.color.replace('bg-', '')})) : [];
            return a;
        }
    },
    methods: {
        percentage(count){
            let a  = (count/this.totalSum) * 100;
            return a+'%';
        },
        release(data){
            this.$refs.released.show(data);
        }
    }
}
</script>