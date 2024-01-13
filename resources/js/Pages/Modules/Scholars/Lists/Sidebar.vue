<template>
    <div class="p-4 d-flex flex-column h-100" style="overflow: auto;">
        <h6 class="fs-11 text-muted text-uppercase mb-3 mt-0">SCHOLARS ENROLLED</h6>
        <div class="d-flex align-items-center">
            <div class="flex-shrink-0">
                <i class="ri-database-2-line fs-17"></i>
            </div>
            <div class="flex-grow-1 ms-3 overflow-hidden">
                <b-progress class="animated-progress progress-sm mb-2" :max="total">
                    <b-progress-bar :value="ongoing" variant="primary" />
                </b-progress>
                <span class="text-muted fs-12 d-block text-truncate"><b>{{ongoing}}</b> out of <b>{{total}}</b> ongoing scholars are enrolled.</span>
            </div>
        </div>
        <hr class="text-muted"/>
        <div>
            <table class="table table-borderless table-sm table-centered align-middle table-nowrap">
                <tbody class="border-0">
                    <tr v-for="(count,index) in statistics" v-bind:key="index">
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
        <hr class="text-muted mt-n1 mb-3"/>
        <div>
            <table class="table table-borderless table-sm table-centered align-middle table-nowrap">
                <tbody class="border-0">
                    <tr v-for="(count,index) in types" v-bind:key="index">
                        <td>
                            <h4 class="text-truncate fs-13 fs-medium mb-0"><i class="ri-stop-fill align-middle fs-18 me-2" :class="colors[index]"></i>{{options2[index]}}</h4>
                        </td>
                        <td class="text-end">
                            <p class="fw-bold mb-0 fs-12" :class="colors[index]">{{count}}</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <hr class="text-muted mt-n1"/>

        <ul class="list-group mb-4" style="cursor: pointer;">
            <li class="list-group-item fs-12" @click="print('awardees')"><i class="bx bxs-printer float-end fs-20 text-light align-middle me-2"></i><i class="bx fs-18 bxs-award text-warning align-middle me-2"></i>Graduates <span class="text-muted fs-11">(w/ Honor)</span> </li>
            <li class="list-group-item fs-12" @click="print('graduates')"><i class="bx bxs-printer float-end fs-20 text-light align-middle me-2"></i><i class="bx fs-18 bxs-graduation text-warning align-middle me-2"></i>List of Graduates</li>
            <li class="list-group-item fs-12" @click="print('scholars')"><i class="bx bxs-printer float-end fs-20 text-light align-middle me-2"></i><i class="ri-team-fill fs-18 text-warning align-middle me-2"></i>List of Scholars</li>
        </ul>

        <div class="mt-auto">
            <b-row class="g-1">
                <b-col lg="4">
                    <button @click="openDownload()" class="btn btn-soft-primary btn-sm w-100" type="button">
                        <div class="btn-content"> Download </div>
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
            </b-row>
        </div>
    </div>
    <Sync ref="sync"/>
    <Download ref="download" @info="refresh()"/>
    <Truncate ref="truncate" @info="refresh()"/>
    <Print :statuses="statuses" :programs="programs" ref="print"/>
</template>
<script>
import Sync from './Modals/Sync.vue';
import Print from './Modals/Print.vue';
import Truncate from './Modals/Truncate.vue';
import Download from './Modals/Download.vue';
export default {
    props: ['total','ongoing','statistics','types','sync_no','statuses','programs'],
    components: { Download, Truncate, Sync, Print },
    data(){
        return {
            options: ['Ongoing Scholars','Graduated Scholars','Total Scholars'],
            options2: ['Undegraduate Scholarhip','Junior Level Science Scholarship'],
            colors: ['text-primary','text-info','text-success'],
        }
    },
    methods: {
        openSync(count){
            this.$refs.sync.show(count);
        },
        openTruncate(){
            this.$refs.truncate.show();
        },
        openDownload(){
            this.$refs.download.show();
        },
        refresh(){
            this.$emit('info',true);
        },
        print(type){
            this.$refs.print.set(type);
        },
    }
}
</script>