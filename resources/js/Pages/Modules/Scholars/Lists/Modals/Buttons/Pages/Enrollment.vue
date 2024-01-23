<template>
    <hr class="text-muted"/>
    <table class="table tablez table-bordered table-nowrap align-middle mb-0 mt-2">
        <thead class="table-light">
            <tr class="fs-11">
                <th class="text-center" width="5%">#</th>
                <th width="20%">Academic Year</th>
                <th class="text-center" width="20%">Level</th>
                <th class="text-center" width="15%">Grades Submitted</th>
                <th class="text-center" width="15%">Benefits Received</th>
                <th class="text-center" width="15%">Status</th>
                <th></th>
            </tr>
        </thead> 
        <tbody>
            <tr class="font-size-11" v-for="(list,index) in enrollments" v-bind:key="index" :class="(list.is_completed) ? 'table-success' : (list.is_missed) ? 'table-danger' : ''">
                <td class="text-center">{{index+1}}</td>
                <td>{{list.semester.academic_year}} <span class="text-muted">| {{list.semester.semester.name}}</span></td>
                <td class="text-center fs-12">{{list.level}}</td>
                <td v-if="list.subjects.length == 0" class="text-center fs-11 text-muted">No Prospectus</td>
                <td v-else class="text-center fs-12">{{checkGrades(list.subjects)}}</td>
                <td class="text-center fs-12">{{checkBenefits(list.benefits)}}</td>
                <td class="text-center">
                    <span v-if="!list.is_enrolled" class="badge border border-warning text-warning" v-b-tooltip.hover title="Still not Enrolled">Not Enrolled</span>
                    <span v-else-if="!list.is_completed" class="badge border border-success text-success" v-b-tooltip.hover title="Enrolled but not completed">Enrolled</span> 
                    <span v-else><i class="text-success ri-checkbox-circle-fill" v-b-tooltip.hover title="Grades and Benefits completed"></i></span>
                </td>
                <td>
                    <button @click="viewGrades(list)" class="btn btn-sm btn-label me-1" :class="(list.is_grades_completed == 1) ? 'btn-success' : 'btn-light' " type="button" :disabled="list.subjects.length == 0">
                        <div class="btn-content"><i class="ri-eye-line label-icon align-middle fs-12 me-2"></i>Grades</div>
                    </button>
                    <button @click="viewBenefits(list)" class="btn btn-sm btn-label" :class="(list.is_benefits_released == 1) ? 'btn-success' : 'btn-light' " type="button">
                        <div class="btn-content"><i class="ri-eye-line label-icon align-middle fs-12 me-2"></i>Benefits</div>
                    </button>
                </td>
            </tr>
        </tbody>
    </table>
    <Benefits ref="benefits"/>
    <Grades ref="grades"/>
</template>
<script>
import Grades from './Modals/Grades.vue';
import Benefits from './Modals/Benefits.vue';
export default {
    components: { Benefits, Grades },
    data(){
        return {
            currentUrl: window.location.origin,
            enrollments: []
        }
    },
    methods: {
        fetch(id){
            axios.get(this.currentUrl+'/scholars/listing', {
                params: {
                    id: id,
                    option: 'enrollments'
                }
            })
            .then(response => {
                this.enrollments = response.data.data;
                this.calculateTotalSum();
            })
            .catch(err => console.log(err));
        },
        viewBenefits(list){
            this.$refs.benefits.show(list);
        },
        viewGrades(list){
            this.$refs.grades.show(list);
        },
        checkBenefits(lists){
            const released = lists.reduce((acc, val) => (val.status_id == 13)  ? acc + 1 : acc, 0);
            return released +' of '+lists.length;
        },
        checkGrades(lists){
            const count = lists.reduce((acc, val) => val.grade != null ? acc + 1 : acc, 0);
            return count +' of '+lists.length;
        },
        calculateTotalSum() {
            var total = 0;
            this.enrollments.map((list) => {
                list.benefits.map((list) => {
                    if(list.status_id == 13){
                    total = parseInt(total) + parseInt(list.amount);
                    }
                });
            });
            this.$emit('total',this.formatMoney(total))
        },
        formatMoney(value) {
            let val = (value/1).toFixed(2).replace(',', '.')
            return '₱'+val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
        },
    }
}
</script>