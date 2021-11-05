<template>
  <div class="container mt-5" style="width: 700px;">
      <div class="card" style="width: 18rem;">
        <div class="card-body">
            <h5 class="card-title">Enter your Task</h5>
            <p>{{ taskName }}</p>
            <div class="input-group mb-3">
                <input v-model="taskName" type="text" class="form-control" aria-label="Dollar amount (with dot and two decimal places)">
                <button @click="addtask" class="btn btn-primary">Add</button>
            </div>
        </div>
    </div> 
    <hr>
    
    <table class="table">
        <thead>
            <tr>
                <td>#</td>
                <td>Name</td>
                <td>Action</td>
            </tr>
        </thead>
        <tbody>
            <tr v-for="(task) in tasks" :key="task.id">
                <td>{{ task.id }}</td>
                <td 
                    class="fw-bold"
                    >
                    <span v-if="!isEdit" :class="{ complete : task.status }">
                        {{ task.name }}
                    </span>
                    <span v-else>
                        <template v-if="seletectedRowId === task.id">
                            <input type="text" v-model="newValue" class="form-control">
                        </template>
                        <template v-else>
                            {{ task.name }}
                        </template>
                    </span>
                </td>

                <td style="width: 220px;">
                    <div style="display: flex; justify-content: space-between;">
                        <button 
                        v-if="!isEdit"
                        :class="{ disabled : task.status }"
                        @click="editTask(task.id)"
                        class="btn btn-sm btn-primary">
                            Edit
                        </button>
                        <button 
                        v-if="isEdit"
                        @click="updateTask(task.id)"
                        class="btn btn-sm btn-primary">
                            Done
                        </button>
                        <button 
                        @click="complete(task.id)"
                        :class="{ disabled : task.status }"
                        class="btn btn-sm btn-danger">
                        Mark as Complete</button>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>



  </div>
</template>

<script>
import axios from 'axios'
import Swal from 'sweetalert2'
const BASE_URL = 'http://backend.test/api/'

const Toast = Swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 3000,
  timerProgressBar: true,
  didOpen: (toast) => {
    toast.addEventListener('mouseenter', Swal.stopTimer)
    toast.addEventListener('mouseleave', Swal.resumeTimer)
  }
})

export default {
    data () {
        return {
            taskName: '',
            tasks: [],
            isEdit: false,
            newValue: '',
            seletectedRowId: 0
        }
    },
    methods: {
        updateTask (id) {
            this.isEdit = false
            axios.post(BASE_URL + 'update/task', {
                id: id,
                newTask: this.newValue
            }).then((res) => {
                if (res.data.success) {
                    Toast.fire({
                        icon: 'success',
                        title: 'Task completed successfully!'
                        })
                    this.fetchData()
                }
            })
        },
        editTask (id) {
            this.seletectedRowId = id
            this.tasks.filter(x => {
                if (x.id === id) {
                    this.newValue = x.name
                }
            })
            this.isEdit = true
        },
        addtask () {
            if(this.taskName == "")
                return Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Task name required',
                })
             
            axios.post(BASE_URL + 'store/task', {
                task: this.taskName
            }).then((res) => {
                if (res.data.success) {
                    Toast.fire({
                        icon: 'success',
                        title: 'Task created successfully!'
                        })
                    this.fetchData()
                }
            })
            this.taskName = ''
        },
        complete (id) {
            axios.post(BASE_URL + 'complete/task', {
                taskId: id
            }).then((res) => {
                if (res.data.success) {
                    Toast.fire({
                        icon: 'success',
                        title: 'Task completed successfully!'
                        })
                    this.fetchData()
                }
            })
        },
        fetchData () {
            axios.get(BASE_URL + 'get/tasks')
            .then(res => {
                this.tasks = res.data.result
            })
        }
    },
    mounted () {
        this.fetchData()
    }
}
</script>

<style>
.complete {
    text-decoration: line-through;
}
</style>