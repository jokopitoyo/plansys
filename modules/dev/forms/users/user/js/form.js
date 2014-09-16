$scope.updateRole = function() {
    if ($scope.userRoles && $scope.userRoles.value.length > 0) {
        var roles = [];
        for (i in $scope.userRoles.value) {
            var c = $scope.userRoles.value[i];
            if (c.role_id != null && roles.indexOf(c.role_id) < 0) {
                roles.push(c.role_id);
            } else {
                $scope.userRoles.value.splice(i, 1);
                $scope.userRoles.updateListView();
            }
        }

        for (i in $scope.userRoles.value) {
            var c = $scope.userRoles.value[i];
            c.is_default_role = 'No';
        }
        $scope.userRoles.value[0].is_default_role = 'Yes';
    } else {
        $scope.userRoles.value.push({
            id: "",
            role_id: "1",
            user_id: "",
            is_default_role: 'Yes'
        });
    }
}

$timeout(function() {
    $scope.updateRole();
}, 0);