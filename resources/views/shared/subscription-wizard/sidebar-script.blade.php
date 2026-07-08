<script>
    function updateWizardSidebar() {
        document.querySelectorAll('.wizard-step-item').forEach((btn) => {
            const step = parseInt(btn.dataset.step, 10);
            btn.classList.remove('is-active', 'is-done');
            btn.removeAttribute('aria-current');

            if (step < currentStep) {
                btn.classList.add('is-done');
            } else if (step === currentStep) {
                btn.classList.add('is-active');
                btn.setAttribute('aria-current', 'step');
            }
        });
    }

    function initWizardSidebarNav() {
        document.querySelectorAll('.wizard-step-item').forEach((btn) => {
            btn.addEventListener('click', () => {
                const step = parseInt(btn.dataset.step, 10);
                if (step < currentStep && typeof goToStep === 'function') {
                    goToStep(step);
                }
            });
        });

        if (typeof updateWizardSidebar === 'function') {
            updateWizardSidebar();
        }

        const successScreen = document.getElementById('successScreen');
        const wizardLayout = document.getElementById('wizardLayout');
        if (successScreen?.classList.contains('active') && wizardLayout) {
            wizardLayout.classList.add('wizard-page--success');
        }
    }
</script>
