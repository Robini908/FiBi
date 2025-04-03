{{-- Alpine.js Utilities for Exam Management --}}
<script>
    document.addEventListener('alpine:init', () => {
        // Exam Form Manager
        Alpine.data('examFormManager', () => ({
            currentStep: 1,
            totalSteps: 3,
            isLoading: false,
            isCreatingGradingSystem: false,
            showClassSections: {},

            init() {
                this.$watch('currentStep', (value) => {
                    // Save form state when changing steps
                    this.$wire.set('currentStep', value);
                });
            },

            progressPercentage() {
                return (this.currentStep / this.totalSteps) * 100;
            },

            nextStep() {
                if (this.currentStep < this.totalSteps) {
                    this.currentStep++;
                }
            },

            prevStep() {
                if (this.currentStep > 1) {
                    this.currentStep--;
                }
            },

            goToStep(step) {
                if (step >= 1 && step <= this.totalSteps) {
                    this.currentStep = step;
                }
            },

            submitForm() {
                this.isLoading = true;
                this.$wire.saveExam()
                    .then(() => {
                        this.isLoading = false;
                    })
                    .catch(() => {
                        this.isLoading = false;
                    });
            },

            toggleClassSections(classId) {
                this.showClassSections[classId] = !this.showClassSections[classId];
            },

            toggleGradingSystemForm() {
                this.isCreatingGradingSystem = !this.isCreatingGradingSystem;
            },

            submitGradingSystemForm() {
                this.isLoading = true;
                this.$wire.saveGradingSystem()
                    .then(() => {
                        this.isLoading = false;
                        this.isCreatingGradingSystem = false;
                    })
                    .catch(() => {
                        this.isLoading = false;
                    });
            }
        }));

        // Exam List Manager
        Alpine.data('examListManager', () => ({
            isLoading: false,
            openFilter: true,
            selectedExamId: null,
            isDeleting: false,
            
            init() {
                // Listen for Livewire events
                Livewire.on('examDeleted', () => {
                    this.isDeleting = false;
                    this.selectedExamId = null;
                });
            },

            toggleFilter() {
                this.openFilter = !this.openFilter;
            },

            resetFilters() {
                this.isLoading = true;
                this.$wire.resetFilters()
                    .then(() => {
                        this.isLoading = false;
                    });
            },

            viewExamDetails(examId) {
                this.$wire.viewExamDetails(examId);
            },

            editExam(examId) {
                this.$wire.editExam(examId);
            },

            confirmDelete(examId) {
                this.selectedExamId = examId;
                this.isDeleting = true;
            },

            cancelDelete() {
                this.isDeleting = false;
                this.selectedExamId = null;
            },

            deleteExam() {
                if (!this.selectedExamId) return;
                
                this.isLoading = true;
                this.$wire.deleteExam(this.selectedExamId)
                    .then(() => {
                        this.isLoading = false;
                    })
                    .catch(() => {
                        this.isLoading = false;
                    });
            }
        }));

        // Grading System Manager
        Alpine.data('gradingSystemManager', () => ({
            isLoading: false,
            showDetails: false,
            selectedGradingId: null,
            activeTab: 'details',
            
            init() {
                // Listen for Livewire events
                Livewire.on('gradingSystemCreated', () => {
                    this.isLoading = false;
                });
            },

            viewGradingSystemDetails(gradingId) {
                this.selectedGradingId = gradingId;
                this.showDetails = true;
                this.$wire.viewGradingSystemDetails(gradingId);
            },

            closeGradingSystemDetails() {
                this.showDetails = false;
                this.selectedGradingId = null;
                this.$wire.closeGradingSystemDetails();
            },

            changeTab(tab) {
                this.activeTab = tab;
            },

            submitGradingSystemForm() {
                this.isLoading = true;
                this.$wire.saveGradingSystem()
                    .then(() => {
                        this.isLoading = false;
                    })
                    .catch(() => {
                        this.isLoading = false;
                    });
            }
        }));
    });
</script> 