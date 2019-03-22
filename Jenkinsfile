pipeline {
    agent any
    
    stages {
        stage('Test') {
            steps {
                sh 'composer dump-autoload'
                sh './vendor/bin/phpunit'
                
            }
        }
    }
    post{
        always{
            xunit thresholds: [
                failed(
                    failureNewThreshold: '2',
                    failureThreshold: '3',
                    unstableNewThreshold: '1',
                    unstableThreshold: '1'
                )
            ],
            tools: [
                PHPUnit(
                    deleteOutputFiles: true,
                    failIfNotNew: true,
                    pattern: '',
                    skipNoTestFiles: true,
                    stopProcessingIfError: true
                )
            ]
            cleanWs()
        }

    }

}