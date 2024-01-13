<template>
    <div class="p-4 d-flex flex-column h-100" style="overflow: auto;">
        <h6 class="fs-11 text-muted text-uppercase mb-3 mt-0">ENDORSEMENTS</h6>
        <div class="d-flex align-items-center">
            <div class="flex-shrink-0">
                <i class="ri-database-2-line fs-17"></i>
            </div>
            <div class="flex-grow-1 ms-3 overflow-hidden">
               <b-progress class="animated-progress progress-sm mb-2" :max="counts.total">
                    <b-progress-bar :value="counts.ongoing" variant="primary" />
                </b-progress>
                <span class="text-muted fs-12 d-block text-truncate"><b>{{counts.ongoing}}</b> out of <b>{{counts.total}}</b> endorsed qualifiers are enrolled.</span>
            </div>
        </div>
        <hr class="text-muted"/>
        <div class="table-responsive">
            <table class="table table-borderless table-sm table-centered align-middle table-nowrap">
                <tbody class="border-0">
                    <tr v-for="(count,index) in counts.types" v-bind:key="index">
                        <td>
                            <h4 class="text-truncate fs-13 fs-medium mb-0">
                                <i class="ri-stop-fill align-middle fs-18 me-2" :class="colors[index]"></i>{{options2[index]}}
                            </h4>
                        </td>
                        <td class="text-end">
                            <p class="fw-bold fs-12 mb-0" :class="colors[index]">{{count}}</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <hr class="text-muted mt-n1"/>
        <div class="table-responsive">
            <table class="table table-borderless table-sm table-centered align-middle table-nowrap">
                <tbody class="border-0">
                    <tr v-for="(count,index) in counts.statistics" v-bind:key="index">
                        <td>
                            <h4 class="text-truncate fs-13 fs-medium mb-0">
                                <i class="ri-stop-fill align-middle fs-18 me-2" :class="colors[index]"></i>{{options[index]}}
                            </h4>
                        </td>
                        <td class="text-end">
                            <p class="fw-bold fs-12 mb-0" :class="colors[index]">{{count}}</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <hr class="text-muted mt-n1"/>
        <div class="mt-auto">
            <!-- <b-row class="g-1">
                <b-col lg="4">
                    <button @click="openImport()" class="btn btn-soft-primary btn-sm w-100" type="button">
                        <div class="btn-content"> Import </div>
                    </button>
                </b-col>
                <b-col lg="4">
                    <button @click="openTruncate()" class="btn btn-soft-primary btn-sm w-100" type="button">
                        <div class="btn-content"> Truncate </div>
                    </button>
                </b-col>
                <b-col lg="4">
                    <button @click="openSync(1)" class="btn btn-primary btn-sm w-100" type="button">
                        <div class="btn-content"> Sync ({{sync_no}})</div>
                    </button>
                </b-col>
            </b-row> -->
        </div>
    </div>
</template>
<script>
export default {
    data(){
        return {
            currentUrl: window.location.origin,
            options: ['Undegraduate Scholarhip','Junior Level Science Scholarship','Total Endorsed Scholars'],
            options2: ['My Endorsements','Endorsements From'],
            colors: ['text-warning','text-primary','text-success'],
            counts: ''
        }
    },
    created(){
        this.fetch();
    },
    methods: {
        fetch() {
            axios.get(this.currentUrl+'/scholars/endorsements', {
                params: {
                    option: 'counts',
                }
            })
            .then(response => {
                this.counts = response.data;
            })
            .catch(err => console.log(err));
        },
    }
}
</script>