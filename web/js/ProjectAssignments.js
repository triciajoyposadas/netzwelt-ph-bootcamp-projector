angular.module('ProjectAssignments', [])
    .controller('ProjectAssignmentsCtrl', ['$rootScope', '$scope', '$http', ProjectAssignmentsCtrl])
;

function ProjectAssignmentsCtrl($rootScope, $scope, $http) {

	$scope.init = ($project, $unassigned_persons) => {
		$scope.project = $project;
		$scope.unassigned_persons = $unassigned_persons;

    };

    $scope.assignPerson = () => {
		
		$person_dropdown = $('#person-dropdown');
		
		person_id = JSON.parse($person_dropdown.val());

		if(!person_id)
			return;

		$http({
			method: 'POST',
			url: ASSIGN_PERSON_URL,
			headers: {
				'Content-Type': 'application/json'
			},
			data: {
				'project_id': $scope.project.id,
				'person_id': person_id
			}
		}).then(function successCallback(response) {

			Materialize.toast('Person was successfully assigned to project.', 4000);

			$person_dropdown.val('');

			$person = $.grep($scope.unassigned_persons, function(e){ return e.id == person_id; })[0];

			let index = $scope.unassigned_persons.indexOf($person);
			
			$scope.unassigned_persons.splice(index, 1);

			$scope.project.persons.push($person);
		}, function errorCallback(response) {
			$person_dropdown.val('');

			Materialize.toast('There was an error on assigning a person to project. Please try again.', 4000);
		});
	};

    $scope.unassignPerson = (person) => {
		
		$http({
			method: 'POST',
			url: UNASSIGN_PERSON_URL,
			headers: {
				'Content-Type': 'application/json'
			},
			data: {
				'project_id': $scope.project.id,
				'person_id': person.id
			}
		}).then(function successCallback(response) {

			Materialize.toast('Person was successfully unassigned from project.', 4000);

			let index = $scope.project.persons.indexOf(person);

			$scope.project.persons.splice(index, 1);

			$scope.unassigned_persons.push(person);
		}, function errorCallback(response) {

			Materialize.toast('There was an error on unassigning a person from project. Please try again.', 4000);
		});
	};
}