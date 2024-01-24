<template>
    <b-modal v-model="showModal" title="For Termination" hide-footer style="--vz-modal-width: 900px;" header-class="p-3 bg-light" class="v-modal-custom" modal-class="zoomIn" centered>    
        <input class="form-control mb-3" v-model="filter.keyword" type="text" placeholder="Search Scholar">
        <hr class="text-muted"/>
        <div class="table-responsive">
            <table class="table table-bordered table-nowrap align-middle mb-0">
                <thead class="table-light">
                    <tr class="fs-11">
                        <th class="text-center">#</th>
                        <th>Name</th>
                        <th class="text-center">Semester</th>
                        <th class="text-center">Level</th>
                        <th class="text-center">Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody v-if="keyword != ''">
                    <tr v-for="(list,index) in lists" v-bind:key="list.id" class="fs-11">
                        <td class="text-center">{{index + 1}}</td>
                        <td>
                            <h5 class="fs-11 mb-0 text-dark">{{ list.scholar.firstname }} {{ list.scholar.lastname }}</h5>
                        </td>
                        <td class="text-center">{{list.semester}} <span class="text-muted fs-11"> ({{list.academic_year}})</span></td>
                        <td class="text-center">{{list.level}}</td>
                        <td class="text-center">
                            <span :class="'badge bg-secondary bg-'+list.status.color">{{list.status.name}}</span>
                        </td>
                        <td class="text-center">
                            <b-button @click="view(list)" variant="soft-primary" v-b-tooltip.hover title="View" size="sm" class="edit-list me-1"><i class="ri-eye-fill align-bottom"></i> </b-button>
                        </td>
                    </tr>
                </tbody>
            </table>
           <Pagination class="ms-2 me-2" v-if="meta" @fetch="fetch" :lists="lists.length" :links="links" :pagination="meta" />
       </div>
   </b-modal>
</template>
<script>
import Pagination from "@/Shared/Components/Pagination.vue";
export default {
   components: { Pagination },
   data(){
       return {
           lists: [],
           meta: {},
           links: {},
           filter: {
               keyword: null
           },
           showModal: false
       }
   },
   watch: {
       "filter.keyword"(newVal){
           this.checkSearchStr(newVal)
       }
   },
   methods: {
        show() {
            this.fetch();
            this.showModal = true;
        },
        checkSearchStr: _.debounce(function(string) {
            this.fetch();
        }, 500),
        fetch(page_url){
            page_url = page_url || '/monitoring';
            axios.get(page_url, {
                params: {option: 'terminations', keyword: this.filter.keyword}
            })
            .then(response => {
                this.lists = response.data.data;
                this.meta = response.data.meta;
                this.links = response.data.links;
            })
            .catch(err => console.log(err));
        },
        hide(){
            this.showModal = false;
        }
   }
}
</script>