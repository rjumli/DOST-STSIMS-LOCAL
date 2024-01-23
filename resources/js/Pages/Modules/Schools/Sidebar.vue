<template>
    <div class="p-4 d-flex flex-column h-100" style="overflow: auto;">
        <h6 class="fs-11 text-muted text-uppercase mb-3 mt-0">SCHOOLS WITH ACTIVE SEMESTER</h6>
        <div class="d-flex align-items-center">
            <div class="flex-shrink-0">
                <i class="ri-database-2-line fs-17"></i>
            </div>
            <div class="flex-grow-1 ms-3 overflow-hidden">
                <b-progress class="animated-progress progress-sm mb-2" :max="statistics.inside.total">
                    <b-progress-bar :value="statistics.active" variant="primary" />
                </b-progress>
                <span class="text-muted fs-12 d-block text-truncate"><b>{{statistics.active}}</b> out of <b>{{statistics.inside.total}}</b> schools with active semester.</span>
            </div>
        </div>
        <hr class="text-muted"/>
        <div>
            <h5 class="fs-12 text-uppercase text-muted">{{statistics.name.acronym}} SCHOOLS HANDLED ({{statistics.name.region.region}})</h5>
            <table class="table table-borderless table-sm table-centered align-middle table-nowrap">
                <tbody class="border-0">
                    <tr v-for="(count,index) in statistics.inside.types" v-bind:key="index">
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

            <h5 class="fs-12 text-uppercase text-muted">OTHER SCHOOLS</h5>
            <table class="table table-borderless table-sm table-centered align-middle table-nowrap">
                <tbody class="border-0">
                    <tr v-for="(count,index) in statistics.outside.types" v-bind:key="index">
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
        <h5 class="fs-12 text-uppercase text-muted">SCHOOLS COUNT</h5>
        <table class="table table-borderless table-sm table-centered align-middle table-nowrap">
            <tbody class="border-0">
                <tr>
                    <td>
                        <h4 class="text-truncate fs-13 fs-medium mb-0">
                            <i class="ri-checkbox-blank-circle-fill align-middle fs-14 me-2" :class="colors[2]"></i>{{statistics.name.region.region}} Schools
                        </h4>
                    </td>
                    <td class="text-end">
                        <p class="fw-bold fs-12 mb-0" :class="colors[2]">{{statistics.inside.total}}</p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <h4 class="text-truncate fs-13 fs-medium mb-0">
                            <i class="ri-checkbox-blank-circle-fill align-middle fs-14 me-2" :class="colors[3]"></i>Overall Schools
                        </h4>
                    </td>
                    <td class="text-end">
                        <p class="fw-bold fs-12 mb-0" :class="colors[3]">{{statistics.outside.total+statistics.inside.total}}</p>
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="mt-auto">
            <b-row class="g-1">
                <b-col lg="4">
                    <button @click="openDownload()" class="btn btn-soft-primary btn-sm w-100" type="button">
                        <div class="btn-content">Download</div>
                    </button>
                </b-col>
                <b-col lg="4">
                    <button @click="openTruncate()" class="btn btn-soft-primary btn-sm w-100" type="button">
                        <div class="btn-content">Truncate</div>
                    </button>
                </b-col>
                <b-col lg="4">
                    <button @click="openSync()" class="btn btn-primary btn-sm w-100" type="button">
                        <div class="btn-content">Request</div>
                    </button>
                </b-col>
            </b-row>
        </div>
    </div>
    <Truncate ref="truncate"/>
    <Download ref="download"/>
</template>
<script>
import Truncate from './Modals/Truncate.vue';
import Download from './Modals/Download.vue';
export default {
    components: { Download, Truncate },
    props: ['statistics'],
    data(){
        return {
            options: ['Public Schools','Private Schools'],
            colors: ['text-warning','text-danger','text-primary','text-success'],
        }
    },
    methods: {
        openDownload(){
            this.$refs.download.show();
        },
        openTruncate(){
            this.$refs.truncate.show();
        }
    }
}
</script>