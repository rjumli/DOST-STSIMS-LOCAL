<template>
   <b-row class="mt-n2 mb-3">
      <div class="col-md-6">
         <div class="page-title-left mt-2">
               <ol class="breadcrumb m-0 fs-15">
                  <li class="breadcrumb-item fw-bold">{{ selected.semester.academic_year }}</li>
                  <li class="breadcrumb-item fw-semibold">
                     <span class="text-success">{{ selected.level.others }}</span>
                  </li>
                  <li class="breadcrumb-item fw-semibold">
                     <span class="text-success">{{ selected.semester.semester.name }}</span>
                  </li>
               </ol>
         </div> 
      </div>
      <div class="col-md-6">
         <div class="hstack float-end  mt-4 mt-sm-0">
               <button v-if="selected.is_locked == 0" @click="openLock(selected)" :disabled="!selected.is_grades_completed" v-b-tooltip.hover title="Lock" class="btn btn-primary btn-md float-end me-1" type="button">
                  <div class="btn-content"><i class="bx bxs-lock-alt"></i></div>
               </button>
               <button v-if="selected.is_locked == 0" @click="openSave()" class="btn btn-success btn-label" type="button">
                  <div class="btn-content"><i class="ri-check-double-line label-icon align-middle fs-16 me-2"></i> Save </div>
               </button>
               <button v-if="$page.props.role == 'Scholarship Coordinator' || $page.props.role == 'Administrator'" @click="openLock(selected)" class="btn btn-danger btn-md btn-label" type="button" v-b-tooltip.hover title="Scholarship Coordinator Only">
                  <div class="btn-content"><i class="ri-lock-unlock-fill label-icon align-middle fs-16 me-2" ></i> Unlock </div>
               </button>
         </div>
      </div>
   </b-row>
   <hr class="text-muted"/>
   <div class="table-responsive">
      <simplebar data-simplebar style="height: calc(100vh - 300px);">
         <table class="table table-centered table-bordered table-nowrap mb-0">
            <thead class="table-light thead-fixed">
               <tr class="font-size-11">
                  <th style="width: 5%;" class="text-center">#</th>
                  <th style="width: 15%;" class="text-center">Code</th>
                  <th class="text-center" style="width: 60%;">Subject</th>
                  <th class="text-center" style="width: 10%;">Unit</th>
                  <th class="text-center" style="width: 10%;">Grade</th>
               </tr>
            </thead>
            <tbody class=" fs-12">
               <tr v-for="(list,index) in lists" v-bind:key="list.id" :class="[(list.is_failed) ? 'table-danger' : '']">
                  <td style="width: 5%;" class="text-center">{{ index+1 }}</td>
                  <td style="width: 15%;" class="text-center fw-semibold">{{list.code}} <span v-if="list.is_lab == true" class="text-warning fw-bold">(Lab)</span></td>
                  <td style="width: 63%;" class="text-center">{{list.subject}} <span v-if="list.is_lab == true" class="text-warning fw-bold">(Lab)</span></td>
                  <td style="width: 10%;" class="text-center">{{list.unit}}</td>
                  <td style="width: 7%;" class="text-center">
                     <select  v-model="list.grade" v-if="selected.is_locked == 0" class="form-select form-select-sm mt-n1 mb-n1" style="">
                           <option :value="null" disabled>-</option>
                           <option :value="list1.grade" v-for="(list1,index) in gradings" v-bind:key="index">{{list1.grade}}</option>
                     </select>
                     <span class="fw-semibold" v-else>{{list.grade}}</span>
                  </td>
               </tr>
            </tbody>
            <tfoot class="table-light tfoot-fixed">
               <tr class="font-size-12">
                     <th colspan="3"></th>
                     <th class="text-center text-primary">{{ units }}</th>
                     <th class="text-center text-primary">{{ total }}</th>
               </tr>
            </tfoot>
         </table>
      </simplebar>
   </div>
   <Grade ref="grade"/>
   <Lock ref="lock"/>
</template>
<script>
import Lock from './Modals/Lock.vue';
import Grade from './Modals/Grade.vue';
import simplebar from 'simplebar-vue';
export default {
   components: { simplebar, Grade, Lock },
   data(){
      return {
         lists: [],
         selected: { semester: { semester: {}}, level: {}},
         gradings: [],
      }
   },
   computed: {
      units: function () {
         var sum = 0;
         if(this.lists != undefined){
            this.lists.forEach(e => {
               sum += Number(e.unit);
            });
         }
         return sum
      },
      total: function () {
         var sum = 0; var tot = 0;
         if(this.lists != undefined){
               this.lists.forEach(e => {
                  if(e.is_nonacademic == false){
                     tot++;
                     if(e.grade == 'F' || e.grade == 'f'){

                     }else{
                           sum += Number(e.grade);
                     }
                  }
               });
         }
         return (sum/tot).toFixed(3);
         this.$forceUpdate();
      },
      failed: function(){
         var count = 0;
         this.lists.forEach(e => {
               if(e.grade == 'F' || e.grade == 'f' || e.grade > 3){
                  count = count + 1;
               }
         });
         return count;
      }
   },
   methods: {
      set(data,gradings){
         this.gradings = gradings;
         this.selected = data;
         this.lists = data.subjects;
      },
      openSave(){
         let data = new FormData();
         data.append('id',this.selected.id);
         data.append('lists',(this.lists.length != 0) ? JSON.stringify(this.lists) : '');
         data.append('file_type','grade');
         data.append('type','grade');
         this.$refs.grade.set(data);
      },
      openLock(data){
         this.$refs.lock.set(data,this.lists);
      }
   }
}
</script>
<style>
.thead-fixed {
   position: sticky;
   top: 0;
   background-color: #fff; /* Set the background color for the fixed header */
   z-index: 1; /* Ensure the fixed header is above the scrolling content */
}
.tfoot-fixed {
   position: sticky;
   bottom: 0;
   background-color: #fff; /* Set the background color for the fixed header */
   z-index: 1; /* Ensure the fixed header is above the scrolling content */
}
</style>