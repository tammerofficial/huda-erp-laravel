@extends('layouts.app')

@section('title', 'Production Workshop Dashboard')
@section('page-title', 'Production Workshop Dashboard')

@section('content')
<div x-data="workshopDashboard()" x-init="init()">
    <div class="max-w-full mx-auto">
        <!-- Header -->
    <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">🏭 Advanced Production Workflow</h2>
                <p class="text-gray-600 mt-1">Complete production management with task transfer and workflow automation</p>
            </div>
            <div class="flex items-center space-x-3 space-x-reverse">
                <button @click="toggleWorkflowMode()" 
                        class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg flex items-center transition-colors">
                    <i class="fas fa-cogs mr-2"></i>
                    <span x-text="workflowMode ? 'Exit Workflow' : 'Workflow Mode'"></span>
                </button>
                <button @click="refreshData()" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center transition-colors">
                    <i class="fas fa-sync-alt mr-2" :class="{'fa-spin': loading}"></i>
                    Refresh
                </button>
                <a href="{{ route('productions.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back
                </a>
            </div>
        </div>
    </div>

        <!-- Statistics Overview -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm font-medium">Active Production Orders</p>
                        <p class="text-3xl font-bold mt-2" x-text="stats.activeOrders"></p>
                    </div>
                    <i class="fas fa-clipboard-list text-5xl text-blue-300 opacity-50"></i>
                </div>
            </div>

            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm font-medium">Active Employees</p>
                        <p class="text-3xl font-bold mt-2" x-text="stats.activeEmployees"></p>
                    </div>
                    <i class="fas fa-users text-5xl text-green-300 opacity-50"></i>
                </div>
            </div>

            <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-orange-100 text-sm font-medium">In Progress</p>
                        <p class="text-3xl font-bold mt-2" x-text="stats.inProgressStages"></p>
                    </div>
                    <i class="fas fa-cogs text-5xl text-orange-300 opacity-50"></i>
                </div>
            </div>

            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100 text-sm font-medium">Completed Today</p>
                        <p class="text-3xl font-bold mt-2" x-text="stats.completedToday"></p>
                    </div>
                    <i class="fas fa-check-circle text-5xl text-purple-300 opacity-50"></i>
                </div>
            </div>
        </div>

        <!-- Workflow Mode Toggle -->
        <div x-show="workflowMode" class="mb-6">
            <div class="bg-gradient-to-r from-purple-500 to-pink-500 rounded-lg shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-bold">🔄 Workflow Management Mode</h3>
                        <p class="text-purple-100 mt-1">Drag and drop tasks between employees and departments</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <button @click="autoAssignTasks()" 
                                class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-lg flex items-center transition-colors">
                            <i class="fas fa-magic mr-2"></i>
                            Auto Assign
                        </button>
                        <button @click="optimizeWorkflow()" 
                                class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-lg flex items-center transition-colors">
                            <i class="fas fa-chart-line mr-2"></i>
                            Optimize
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Production Orders Overview -->
        <div x-show="workflowMode" class="mb-6">
            <div class="bg-white rounded-lg shadow-sm border p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">📋 Active Production Orders</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <template x-for="order in activeOrders" :key="order.id">
                        <div class="border rounded-lg p-4 hover:shadow-md transition-shadow">
                            <div class="flex items-center justify-between mb-2">
                                <span class="font-medium text-gray-900" x-text="order.production_number"></span>
                                <span class="px-2 py-1 rounded text-xs" 
                                      :class="{
                                          'bg-blue-100 text-blue-800': order.status === 'pending',
                                          'bg-yellow-100 text-yellow-800': order.status === 'in-progress',
                                          'bg-green-100 text-green-800': order.status === 'completed'
                                      }"
                                      x-text="order.status"></span>
                            </div>
                            <p class="text-sm text-gray-600 mb-2" x-text="order.product_name"></p>
                            <div class="text-xs text-gray-500">
                                <p>Customer: <span x-text="order.customer_name"></span></p>
                                <p>Priority: <span x-text="order.priority"></span></p>
                            </div>
                            <div class="mt-3">
                                <div class="flex items-center justify-between text-xs text-gray-600 mb-1">
                                    <span>Progress</span>
                                    <span x-text="order.progress + '%'"></span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" 
                                         :style="'width: ' + order.progress + '%'"></div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <!-- Departments Tabs -->
        <div class="bg-white rounded-lg shadow-sm border mb-6">
            <div class="border-b border-gray-200">
                <nav class="flex space-x-1 overflow-x-auto p-2" aria-label="Tabs">
                    <button @click="selectedDepartment = 'all'"
                            :class="selectedDepartment === 'all' ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:bg-gray-100'"
                            class="px-4 py-2 rounded-lg font-medium text-sm whitespace-nowrap transition-colors">
                        <i class="fas fa-th-large mr-2"></i>
                        All Departments
                    </button>
                    <button @click="selectedDepartment = 'Cutting'"
                            :class="selectedDepartment === 'Cutting' ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:bg-gray-100'"
                            class="px-4 py-2 rounded-lg font-medium text-sm whitespace-nowrap transition-colors">
                        <i class="fas fa-cut mr-2"></i>
                        ✂️ Cutting
                    </button>
                    <button @click="selectedDepartment = 'Sewing'"
                            :class="selectedDepartment === 'Sewing' ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:bg-gray-100'"
                            class="px-4 py-2 rounded-lg font-medium text-sm whitespace-nowrap transition-colors">
                        <i class="fas fa-sewing-machine mr-2"></i>
                        🪡 Sewing
                    </button>
                    <button @click="selectedDepartment = 'Embroidery'"
                            :class="selectedDepartment === 'Embroidery' ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:bg-gray-100'"
                            class="px-4 py-2 rounded-lg font-medium text-sm whitespace-nowrap transition-colors">
                        <i class="fas fa-palette mr-2"></i>
                        🎨 Embroidery
                    </button>
                    <button @click="selectedDepartment = 'Quality Assurance'"
                            :class="selectedDepartment === 'Quality Assurance' ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:bg-gray-100'"
                            class="px-4 py-2 rounded-lg font-medium text-sm whitespace-nowrap transition-colors">
                        <i class="fas fa-check-double mr-2"></i>
                        👔 Quality Control
                    </button>
                    <button @click="selectedDepartment = 'Finishing'"
                            :class="selectedDepartment === 'Finishing' ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:bg-gray-100'"
                            class="px-4 py-2 rounded-lg font-medium text-sm whitespace-nowrap transition-colors">
                        <i class="fas fa-box mr-2"></i>
                        📦 Finishing
                    </button>
                </nav>
            </div>
        </div>

        <!-- Employees Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
            <template x-for="employee in filteredEmployees" :key="employee.id">
                <div class="bg-white rounded-lg shadow-sm border hover:shadow-md transition-shadow">
                    <!-- Employee Header -->
                    <div class="p-4 border-b border-gray-200"
                         :class="{
                             'bg-green-50': employee.current_task,
                             'bg-gray-50': !employee.current_task
                         }">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3 space-x-reverse">
                                <div class="w-12 h-12 rounded-full flex items-center justify-center text-white font-bold text-lg"
                                     :class="{
                                         'bg-green-500': employee.current_task,
                                         'bg-gray-400': !employee.current_task
                                     }">
                                    <span x-text="employee.initials"></span>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-900" x-text="employee.name"></h3>
                                    <p class="text-sm text-gray-600" x-text="employee.position"></p>
                                    <p class="text-xs text-gray-500" x-text="employee.employee_id"></p>
                                </div>
                            </div>
                            <div>
                                <span class="px-2 py-1 rounded-full text-xs font-medium"
                                      :class="employee.current_task ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'">
                                    <i class="fas fa-circle text-xs mr-1"></i>
                                    <span x-text="employee.current_task ? 'Busy' : 'Available'"></span>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Current Task -->
                    <div class="p-4" x-show="employee.current_task">
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mb-3">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs font-medium text-blue-700">🔄 Currently Working On:</span>
                                <span class="text-xs text-blue-600" x-text="employee.current_task?.stage"></span>
                            </div>
                            <p class="font-medium text-gray-900 text-sm mb-1" x-text="employee.current_task?.production_number"></p>
                            <p class="text-xs text-gray-600 mb-2" x-text="employee.current_task?.product_name"></p>
                            <div class="flex items-center justify-between text-xs">
                                <span class="text-gray-600">
                                    <i class="fas fa-clock mr-1"></i>
                                    Started: <span x-text="employee.current_task?.started_at"></span>
                                </span>
                                <button @click="completeStage(employee.current_task.stage_id)"
                                        class="px-2 py-1 bg-green-600 text-white rounded hover:bg-green-700 transition-colors">
                                    <i class="fas fa-check mr-1"></i>
                                    Complete
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Assigned Tasks Queue -->
                    <div class="p-4 border-t border-gray-100">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="font-medium text-gray-900 text-sm">
                                <i class="fas fa-tasks mr-1 text-gray-400"></i>
                                Task Queue (<span x-text="employee.tasks.length"></span>)
                            </h4>
                            <button @click="openAssignModal(employee)"
                                    class="text-xs px-2 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors">
                                <i class="fas fa-plus mr-1"></i>
                                Assign
                            </button>
                        </div>

                        <div x-show="!employee.tasks || !Array.isArray(employee.tasks) || employee.tasks.length === 0" class="text-center py-4 text-gray-400 text-sm">
                            <i class="fas fa-inbox text-2xl mb-2"></i>
                            <p>No pending tasks</p>
                        </div>

                        <div class="space-y-2" x-show="employee.tasks && Array.isArray(employee.tasks) && employee.tasks.length > 0">
                            <template x-for="(task, index) in (employee.tasks || []).slice(0, 3)" :key="'task-' + index + '-' + (task.stage_id || 'no-id')">
                                <div class="bg-gray-50 rounded p-2 text-xs">
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="font-medium text-gray-900" x-text="task.production_number"></span>
                                        <span class="px-1.5 py-0.5 bg-yellow-100 text-yellow-800 rounded text-xs" x-text="task.stage"></span>
                                    </div>
                                    <p class="text-gray-600 truncate" x-text="task.product_name"></p>
                                </div>
                            </template>
                            <button x-show="employee.tasks && employee.tasks.length > 3"
                                    @click="showAllTasks(employee)"
                                    class="w-full text-xs text-blue-600 hover:text-blue-800 py-1">
                                + <span x-text="(employee.tasks || []).length - 3"></span> more tasks
                            </button>
                        </div>
                    </div>

                    <!-- Employee Stats -->
                    <div class="p-3 bg-gray-50 border-t border-gray-200 grid grid-cols-3 gap-2 text-center">
                        <div>
                            <p class="text-xs text-gray-600">Today</p>
                            <p class="text-lg font-bold text-gray-900" x-text="employee.stats.today"></p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-600">This Week</p>
                            <p class="text-lg font-bold text-gray-900" x-text="employee.stats.week"></p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-600">Total</p>
                            <p class="text-lg font-bold text-gray-900" x-text="employee.stats.total"></p>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <!-- Empty State -->
        <div x-show="filteredEmployees.length === 0" class="bg-white rounded-lg shadow-sm border p-12 text-center">
            <i class="fas fa-users-slash text-gray-300 text-6xl mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">No Employees Found</h3>
            <p class="text-gray-600">No employees found in the selected department</p>
        </div>
    </div>

    <!-- Assign Task Modal -->
    <div x-show="assignModal.show" 
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto" 
         @keydown.escape.window="assignModal.show = false">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" @click="assignModal.show = false">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                <div class="bg-white px-6 py-5 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">
                            <i class="fas fa-user-plus mr-2 text-blue-600"></i>
                            Assign Task to <span x-text="assignModal.employee?.name"></span>
                        </h3>
                        <button @click="assignModal.show = false" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                </div>

                <div class="bg-white px-6 py-4 max-h-96 overflow-y-auto">
                    <div class="space-y-3">
                        <template x-for="stage in availableStages" :key="stage.id">
                            <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-400 cursor-pointer transition-colors"
                                 @click="assignStage(stage.id)">
                                <div class="flex items-center justify-between mb-2">
                                    <h4 class="font-medium text-gray-900" x-text="stage.production_number"></h4>
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs font-medium" x-text="stage.stage_name"></span>
                                </div>
                                <p class="text-sm text-gray-600 mb-2" x-text="stage.product_name"></p>
                                <div class="flex items-center justify-between text-xs text-gray-500">
                                    <span>
                                        <i class="fas fa-user mr-1"></i>
                                        <span x-text="stage.customer_name"></span>
                                    </span>
                                    <span class="px-2 py-1 rounded-full text-xs"
                                          :class="{
                                              'bg-red-100 text-red-800': stage.priority === 'urgent',
                                              'bg-orange-100 text-orange-800': stage.priority === 'high',
                                              'bg-blue-100 text-blue-800': stage.priority === 'normal',
                                              'bg-gray-100 text-gray-800': stage.priority === 'low'
                                          }">
                                        <i class="fas fa-flag mr-1"></i>
                                        <span x-text="stage.priority"></span>
                                    </span>
                                </div>
                            </div>
                        </template>

                        <div x-show="availableStages.length === 0" class="text-center py-8 text-gray-400">
                            <i class="fas fa-inbox text-4xl mb-3"></i>
                            <p>No pending stages available for assignment</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 px-6 py-4">
                    <button @click="assignModal.show = false"
                            class="w-full px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function workshopDashboard() {
    return {
        loading: false,
        selectedDepartment: 'all',
        employees: @json($employees).map(employee => ({
            ...employee,
            tasks: Array.isArray(employee.tasks) ? employee.tasks : [],
            stats: employee.stats || { today: 0, week: 0, total: 0 }
        })),
        stages: @json($pendingStages),
        stats: {
            activeOrders: {{ $stats['activeOrders'] }},
            activeEmployees: {{ $stats['activeEmployees'] }},
            inProgressStages: {{ $stats['inProgressStages'] }},
            completedToday: {{ $stats['completedToday'] }}
        },
        assignModal: {
            show: false,
            employee: null
        },
        workflowMode: false,
        selectedEmployee: null,
        selectedTask: null,
        activeOrders: [],
        departments: [],

        init() {
            console.log('Dashboard initialized');
            // Ensure all employees have proper structure
            this.employees = this.employees.map(employee => ({
                ...employee,
                tasks: Array.isArray(employee.tasks) ? employee.tasks : [],
                stats: employee.stats || { today: 0, week: 0, total: 0 }
            }));
            console.log('Employees initialized:', this.employees);
            
            // Auto refresh every 30 seconds
            setInterval(() => {
                console.log('Auto refresh triggered');
                this.refreshData();
            }, 30000);
        },

        get filteredEmployees() {
            if (this.selectedDepartment === 'all') {
                return this.employees;
            }
            return this.employees.filter(emp => emp.department === this.selectedDepartment);
        },

        get availableStages() {
            const employeeId = this.assignModal.employee?.id;
            const employeeDept = this.assignModal.employee?.department;
            
            // Filter stages based on employee department
            return this.stages.filter(stage => {
                if (employeeDept === 'Cutting') return stage.stage_name === 'cutting';
                if (employeeDept === 'Sewing') return stage.stage_name === 'sewing';
                if (employeeDept === 'Embroidery') return stage.stage_name === 'embroidery';
                if (employeeDept === 'Quality Assurance') return stage.stage_name === 'quality_check';
                if (employeeDept === 'Finishing') return stage.stage_name === 'ironing';
                return false;
            });
        },

        async refreshData() {
            this.loading = true;
            try {
                console.log('Refreshing data...');
                const response = await fetch('{{ route("productions.dashboard-data") }}');
                const data = await response.json();
                console.log('Data received:', data);
                
                // Ensure all employees have tasks array and stats
                this.employees = data.employees.map(employee => ({
                    ...employee,
                    tasks: Array.isArray(employee.tasks) ? employee.tasks : [],
                    stats: employee.stats || { today: 0, week: 0, total: 0 }
                }));
                this.stages = data.pendingStages || [];
                this.stats = data.stats || {};
                console.log('Data updated successfully');
            } catch (error) {
                console.error('Failed to refresh data:', error);
            } finally {
                this.loading = false;
            }
        },

        openAssignModal(employee) {
            this.assignModal.employee = employee;
            this.assignModal.show = true;
        },

        toggleWorkflowMode() {
            this.workflowMode = !this.workflowMode;
            if (this.workflowMode) {
                this.organizeByDepartments();
                this.loadActiveOrders();
            }
        },

        organizeByDepartments() {
            const deptMap = {};
            this.employees.forEach(employee => {
                // Ensure tasks is an array
                if (!Array.isArray(employee.tasks)) {
                    employee.tasks = [];
                }
                
                if (!deptMap[employee.department]) {
                    deptMap[employee.department] = {
                        name: employee.department,
                        employees: [],
                        activeTasks: 0,
                        expanded: true,
                        color: this.getDepartmentColor(employee.department),
                        icon: this.getDepartmentIcon(employee.department)
                    };
                }
                deptMap[employee.department].employees.push(employee);
                deptMap[employee.department].activeTasks += employee.tasks.length;
            });
            this.departments = Object.values(deptMap);
        },

        getDepartmentColor(department) {
            const colors = {
                'Cutting': 'bg-red-500',
                'Sewing': 'bg-blue-500',
                'Embroidery': 'bg-purple-500',
                'Quality Assurance': 'bg-green-500',
                'Finishing': 'bg-yellow-500',
                'Warehouse': 'bg-gray-500'
            };
            return colors[department] || 'bg-gray-500';
        },

        getDepartmentIcon(department) {
            const icons = {
                'Cutting': 'fas fa-cut',
                'Sewing': 'fas fa-sewing-machine',
                'Embroidery': 'fas fa-palette',
                'Quality Assurance': 'fas fa-check-circle',
                'Finishing': 'fas fa-hammer',
                'Warehouse': 'fas fa-warehouse'
            };
            return icons[department] || 'fas fa-cog';
        },

        toggleDepartment(departmentName) {
            const dept = this.departments.find(d => d.name === departmentName);
            if (dept) {
                dept.expanded = !dept.expanded;
            }
        },

        selectEmployee(employee) {
            this.selectedEmployee = employee;
        },

        selectTask(task, employee) {
            this.selectedTask = task;
            this.selectedEmployee = employee;
        },

        transferTasks(fromEmployee) {
            if (!this.selectedTask || !this.selectedEmployee) {
                alert('Please select a task and target employee first');
                return;
            }
            
            this.moveTask(this.selectedTask, fromEmployee, this.selectedEmployee);
        },

        moveTask(task, fromEmployee, toEmployee) {
            // Check if task has valid stage_id
            if (!task.stage_id || task.stage_id === 'undefined') {
                console.warn('Cannot move task without valid stage_id:', task);
                return;
            }
            
            // Remove task from source employee
            fromEmployee.tasks = fromEmployee.tasks.filter(t => t.stage_id !== task.stage_id);
            
            // Add task to target employee
            toEmployee.tasks.push(task);
            
            // Update backend only if stage_id is valid
            this.updateTaskAssignment(task.stage_id, toEmployee.id);
            
            // Clear selection
            this.selectedTask = null;
            this.selectedEmployee = null;
        },

        async updateTaskAssignment(stageId, employeeId) {
            // Check if stageId is valid
            if (!stageId || stageId === 'undefined') {
                console.warn('Invalid stage ID:', stageId);
                return;
            }
            
            try {
                const response = await fetch(`/production-stages/${stageId}/assign`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    },
                    body: JSON.stringify({
                        employee_id: employeeId
                    })
                });
                
                if (response.ok) {
                    console.log('Task assignment updated successfully');
                } else {
                    console.error('Failed to update task assignment:', response.status);
                }
            } catch (error) {
                console.error('Failed to update task assignment:', error);
            }
        },

        async loadActiveOrders() {
            try {
                const response = await fetch('{{ route("productions.active-orders") }}');
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                const data = await response.json();
                this.activeOrders = data.orders || [];
                console.log('Active orders loaded:', this.activeOrders);
            } catch (error) {
                console.error('Failed to load active orders:', error);
                this.activeOrders = [];
            }
        },

        autoAssignTasks() {
            // Auto-assign tasks based on department and workload
            this.employees.forEach(employee => {
                if (employee.tasks.length === 0) {
                    // Find suitable tasks for this employee
                    const suitableTasks = this.findSuitableTasks(employee);
                    suitableTasks.forEach(task => {
                        // Only move tasks with valid stage_id
                        if (task.stage_id && task.stage_id !== 'undefined') {
                            this.moveTask(task, { tasks: this.stages }, employee);
                        }
                    });
                }
            });
        },

        findSuitableTasks(employee) {
            return this.stages.filter(stage => {
                // Check if stage has valid stage_id
                if (!stage.stage_id || stage.stage_id === 'undefined') {
                    return false;
                }
                
                const employeeDept = employee.department.toLowerCase();
                const stageName = stage.stage_name.toLowerCase();
                
                if (employeeDept === 'cutting') return stageName === 'cutting';
                if (employeeDept === 'sewing') return stageName === 'sewing';
                if (employeeDept === 'embroidery') return stageName === 'embroidery';
                if (employeeDept === 'quality assurance') return stageName === 'quality_check';
                if (employeeDept === 'finishing') return stageName === 'ironing';
                return false;
            });
        },

        optimizeWorkflow() {
            // Optimize workflow by balancing workload
            const totalTasks = this.employees.reduce((sum, emp) => sum + emp.tasks.length, 0);
            const avgTasks = Math.floor(totalTasks / this.employees.length);
            
            // Redistribute tasks to balance workload
            this.employees.forEach(employee => {
                if (employee.tasks.length > avgTasks + 1) {
                    const excessTasks = employee.tasks.splice(avgTasks);
                    excessTasks.forEach(task => {
                        // Only move tasks with valid stage_id
                        if (task.stage_id && task.stage_id !== 'undefined') {
                            const targetEmployee = this.findLeastBusyEmployee(employee.department);
                            if (targetEmployee) {
                                this.moveTask(task, employee, targetEmployee);
                            }
                        }
                    });
                }
            });
        },

        findLeastBusyEmployee(department) {
            return this.employees
                .filter(emp => emp.department === department)
                .sort((a, b) => a.tasks.length - b.tasks.length)[0];
        },

        async assignStage(stageId) {
            try {
                const response = await fetch(`/production-stages/${stageId}/assign`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    },
                    body: JSON.stringify({
                        employee_id: this.assignModal.employee.id
                    })
                });

                if (response.ok) {
                    await this.refreshData();
                    this.assignModal.show = false;
                    // Show success message
                    alert('Task assigned successfully!');
                }
            } catch (error) {
                console.error('Failed to assign task:', error);
                alert('Failed to assign task');
            }
        },

        async completeStage(stageId) {
            if (!confirm('Mark this stage as complete?')) return;

            try {
                const response = await fetch(`/production-stages/${stageId}/complete`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    }
                });

                if (response.ok) {
                    await this.refreshData();
                    alert('Stage completed successfully!');
                }
            } catch (error) {
                console.error('Failed to complete stage:', error);
                alert('Failed to complete stage');
            }
        },

        showAllTasks(employee) {
            // Could open a modal or expand the task list
            alert(`All tasks for ${employee.name}:\n\n` + 
                  employee.tasks.map(t => `${t.production_number} - ${t.stage}`).join('\n'));
        }
    }
}
</script>

<style>
[x-cloak] {
    display: none !important;
}
</style>
@endsection

