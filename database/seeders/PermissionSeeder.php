<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            ['name' => "portal.research.documents", 'description' => "Research Documents", 'module' => "dashboard", 'module_name' => "Dashboard", 'guard_name' => "web"],
            ['name' => "portal.completed.research", 'description' => "Completed Research", 'module' => "dashboard", 'module_name' => "Dashboard", 'guard_name' => "web"],
            ['name' => "portal.posted.research", 'description' => "Posted Research", 'module' => "dashboard", 'module_name' => "Dashboard", 'guard_name' => "web"],
            ['name' => "portal.researchers", 'description' => "Researchers", 'module' => "dashboard", 'module_name' => "Dashboard", 'guard_name' => "web"],
            ['name' => "portal.submitted.research", 'description' => "Submitted Research", 'module' => "dashboard", 'module_name' => "Dashboard", 'guard_name' => "web"],
            ['name' => "portal.student.research", 'description' => "Student Research", 'module' => "dashboard", 'module_name' => "Dashboard", 'guard_name' => "web"],
            ['name' => "portal.archives", 'description' => "Archives", 'module' => "dashboard", 'module_name' => "Dashboard", 'guard_name' => "web"],
            ['name' => "portal.user.application", 'description' => "User Applications", 'module' => "dashboard", 'module_name' => "Dashboard", 'guard_name' => "web"],
            ['name' => "portal.posted.statistics", 'description' => "Posted Research Statistics", 'module' => "dashboard", 'module_name' => "Dashboard", 'guard_name' => "web"],
            ['name' => "portal.research.statuses", 'description' => "All Research Status", 'module' => "dashboard", 'module_name' => "Dashboard", 'guard_name' => "web"],
            ['name' => "portal.research.statistics", 'description' => "Research Statistics", 'module' => "dashboard", 'module_name' => "Dashboard", 'guard_name' => "web"],
            ['name' => "portal.completed.statistics", 'description' => "Completed Research Statistics", 'module' => "dashboard", 'module_name' => "Dashboard", 'guard_name' => "web"],

            ['name' => "portal.users_kyc.index", 'description' => "List of User Applications", 'module' => "users_kyc", 'module_name' => "User Applications", 'guard_name' => "web"],
            ['name' => "portal.users_kyc.approved", 'description' => "List of Approved User Applications", 'module' => "users_kyc", 'module_name' => "User Applications", 'guard_name' => "web"],
            ['name' => "portal.users_kyc.rejected", 'description' => "List of Rejected User Applications", 'module' => "users_kyc", 'module_name' => "User Applications", 'guard_name' => "web"],
            ['name' => "portal.users_kyc.view", 'description' => "View User Application Details", 'module' => "users_kyc", 'module_name' => "User Applications", 'guard_name' => "web"],
            ['name' => "portal.users_kyc.update_status", 'description' => "Verify User Application", 'module' => "users_kyc", 'module_name' => "User Applications", 'guard_name' => "web"],
            ['name' => "portal.users_kyc.search", 'description' => "Search Record", 'module' => "users_kyc", 'module_name' => "User Applications", 'guard_name' => "web"],

            ['name' => "portal.users.index", 'description' => "List of Users", 'module' => "users", 'module_name' => "Account Management", 'guard_name' => "web"],
            ['name' => "portal.users.view", 'description' => "View User Details", 'module' => "users", 'module_name' => "Account Management", 'guard_name' => "web"],
            ['name' => "portal.users.create", 'description' => "Create New User", 'module' => "users", 'module_name' => "Account Management", 'guard_name' => "web"],
            ['name' => "portal.users.update", 'description' => "Update User Details", 'module' => "users", 'module_name' => "Account Management", 'guard_name' => "web"],
            ['name' => "portal.users.delete", 'description' => "Delete User", 'module' => "users", 'module_name' => "Account Management", 'guard_name' => "web"],
            ['name' => "portal.users.search", 'description' => "Search Record", 'module' => "users", 'module_name' => "Account Management", 'guard_name' => "web"],
            ['name' => "portal.users.edit_password", 'description' => "Reset User Password", 'module' => "users", 'module_name' => "Account Management", 'guard_name' => "web"],
            ['name' => "portal.users.update_status", 'description' => "Activate or Deactivate User", 'module' => "users", 'module_name' => "Account Management", 'guard_name' => "web"],
            ['name' => "portal.users.search", 'description' => "Search Record", 'module' => "users", 'module_name' => "Account Management", 'guard_name' => "web"],

            ['name' => "portal.research.index", 'description' => "List of Pending Research", 'module' => "research", 'module_name' => "Research", 'guard_name' => "web"],
            ['name' => "portal.research.approved", 'description' => "List of Approved Research", 'module' => "research", 'module_name' => "Research", 'guard_name' => "web"],
            ['name' => "portal.research.for_revision", 'description' => "List of For Revision Research", 'module' => "research", 'module_name' => "Research", 'guard_name' => "web"],
            ['name' => "portal.research.rejected", 'description' => "List of Rejected Research", 'module' => "research", 'module_name' => "Research", 'guard_name' => "web"],
            ['name' => "portal.research.view", 'description' => "View Research Details", 'module' => "research", 'module_name' => "Research", 'guard_name' => "web"],
            ['name' => "portal.research.create", 'description' => "Create New Research", 'module' => "research", 'module_name' => "Research", 'guard_name' => "web"],
            ['name' => "portal.research.update", 'description' => "Update Research Details", 'module' => "research", 'module_name' => "Research", 'guard_name' => "web"],
            ['name' => "portal.research.update_share", 'description' => "Share Research Access", 'module' => "research", 'module_name' => "Research", 'guard_name' => "web"],
            ['name' => "portal.research.delete", 'description' => "Delete Research", 'module' => "research", 'module_name' => "Research", 'guard_name' => "web"],
            ['name' => "portal.research.download", 'description' => "Download Research File", 'module' => "research", 'module_name' => "Research", 'guard_name' => "web"],
            ['name' => "portal.research.search", 'description' => "Search Record", 'module' => "research", 'module_name' => "Research", 'guard_name' => "web"],

            ['name' => "portal.student_research.index", 'description' => "List of Student Pending Research", 'module' => "student_research", 'module_name' => "Student Research", 'guard_name' => "web"],
            ['name' => "portal.student_research.approved", 'description' => "List of Student Approved Research", 'module' => "student_research", 'module_name' => "Student Research", 'guard_name' => "web"],
            ['name' => "portal.student_research.for_revision", 'description' => "List of Student For Revision Research", 'module' => "student_research", 'module_name' => "Student Research", 'guard_name' => "web"],
            ['name' => "portal.student_research.rejected", 'description' => "List of Student Rejected Research", 'module' => "student_research", 'module_name' => "Student Research", 'guard_name' => "web"],
            ['name' => "portal.student_research.view", 'description' => "View Student Research Details", 'module' => "student_research", 'module_name' => "Student Research", 'guard_name' => "web"],
            ['name' => "portal.student_research.update_status", 'description' => "Evaluate Student Research", 'module' => "student_research", 'module_name' => "Student Research", 'guard_name' => "web"],
            ['name' => "portal.student_research.update_share", 'description' => "Share Research Access", 'module' => "student_research", 'module_name' => "Student Research", 'guard_name' => "web"],
            ['name' => "portal.student_research.delete", 'description' => "Delete Student Research", 'module' => "student_research", 'module_name' => "Student Research", 'guard_name' => "web"],
            ['name' => "portal.student_research.download", 'description' => "Download Student Research File", 'module' => "student_research", 'module_name' => "Student Research", 'guard_name' => "web"],
            ['name' => "portal.student_research.search", 'description' => "Search Record", 'module' => "student_research", 'module_name' => "Student Research", 'guard_name' => "web"],

            ['name' => "portal.all_research.index", 'description' => "List of All Research", 'module' => "all_research", 'module_name' => "All Research", 'guard_name' => "web"],
            ['name' => "portal.all_research.approved", 'description' => "List of All Research Approved", 'module' => "all_research", 'module_name' => "All Research", 'guard_name' => "web"],
            ['name' => "portal.all_research.for_revision", 'description' => "List of All Research For Revision", 'module' => "all_research", 'module_name' => "All Research", 'guard_name' => "web"],
            ['name' => "portal.all_research.rejected", 'description' => "List of All Research Rejected", 'module' => "all_research", 'module_name' => "All Research", 'guard_name' => "web"],
            ['name' => "portal.all_research.view", 'description' => "View Research Details", 'module' => "all_research", 'module_name' => "All Research", 'guard_name' => "web"],
            ['name' => "portal.all_research.delete", 'description' => "Delete Research", 'module' => "all_research", 'module_name' => "All Research", 'guard_name' => "web"],
            ['name' => "portal.all_research.download", 'description' => "Download Research File", 'module' => "all_research", 'module_name' => "All Research", 'guard_name' => "web"],
            ['name' => "portal.all_research.search", 'description' => "Search Record", 'module' => "all_research", 'module_name' => "All Research", 'guard_name' => "web"],

            ['name' => "portal.completed_research.index", 'description' => "List of Completed Research", 'module' => "completed_research", 'module_name' => "Completed Research", 'guard_name' => "web"],
            ['name' => "portal.completed_research.view", 'description' => "View Completed Research Details", 'module' => "completed_research", 'module_name' => "Completed Research", 'guard_name' => "web"],
            ['name' => "portal.completed_research.create", 'description' => "Create New Completed Research", 'module' => "completed_research", 'module_name' => "Completed Research", 'guard_name' => "web"],
            ['name' => "portal.completed_research.update", 'description' => "Update Completed Research Details", 'module' => "completed_research", 'module_name' => "Completed Research", 'guard_name' => "web"],
            ['name' => "portal.completed_research.update_status", 'description' => "Evaluate Completed Research", 'module' => "completed_research", 'module_name' => "Completed Research", 'guard_name' => "web"],
            ['name' => "portal.completed_research.delete", 'description' => "Delete Completed Research", 'module' => "completed_research", 'module_name' => "Completed Research", 'guard_name' => "web"],
            ['name' => "portal.completed_research.download", 'description' => "Download Completed Research File", 'module' => "completed_research", 'module_name' => "Completed Research", 'guard_name' => "web"],
            ['name' => "portal.completed_research.search", 'description' => "Search Record", 'module' => "completed_research", 'module_name' => "Completed Research", 'guard_name' => "web"],

            ['name' => "portal.posted_research.index", 'description' => "List of Posted Research", 'module' => "posted_research", 'module_name' => "Posted Research", 'guard_name' => "web"],
            ['name' => "portal.posted_research.create", 'description' => "Post Research Document", 'module' => "posted_research", 'module_name' => "Posted Research", 'guard_name' => "web"],
            ['name' => "portal.posted_research.view", 'description' => "View Posted Research Details", 'module' => "posted_research", 'module_name' => "Posted Research", 'guard_name' => "web"],
            ['name' => "portal.posted_research.download", 'description' => "Download Posted Research File", 'module' => "posted_research", 'module_name' => "Posted Research", 'guard_name' => "web"],
            ['name' => "portal.posted_research.search", 'description' => "Search Record", 'module' => "posted_research", 'module_name' => "Posted Research", 'guard_name' => "web"],

            ['name' => "portal.archives.index", 'description' => "List of Archives Research", 'module' => "archives", 'module_name' => "Archives", 'guard_name' => "web"],
            ['name' => "portal.archives.completed", 'description' => "List of Archives Completed Research", 'module' => "archives", 'module_name' => "Archives", 'guard_name' => "web"],
            ['name' => "portal.archives.view", 'description' => "View Archive Research Details", 'module' => "archives", 'module_name' => "Archives", 'guard_name' => "web"],
            ['name' => "portal.archives.delete", 'description' => "Delete Archive Research", 'module' => "archives", 'module_name' => "Archives", 'guard_name' => "web"],
            ['name' => "portal.archives.restore", 'description' => "Restore Archive Research", 'module' => "archives", 'module_name' => "Archives", 'guard_name' => "web"],
            ['name' => "portal.archives.search", 'description' => "Search Record", 'module' => "archives", 'module_name' => "Archives", 'guard_name' => "web"],

            ['name' => "portal.research_reports.index", 'description' => "List of Research Report", 'module' => "research_reports", 'module_name' => "Research Reports", 'guard_name' => "web"],
            ['name' => "portal.research_reports.completed", 'description' => "List of Completed Research Report", 'module' => "research_reports", 'module_name' => "Research Reports", 'guard_name' => "web"],
            ['name' => "portal.research_reports.posted", 'description' => "List of Posted Research Report", 'module' => "research_reports", 'module_name' => "Research Reports", 'guard_name' => "web"],
            ['name' => "portal.research_reports.summary", 'description' => "Summary Report", 'module' => "research_reports", 'module_name' => "Research Reports", 'guard_name' => "web"],
            ['name' => "portal.research_reports.export", 'description' => "Generate Report", 'module' => "research_reports", 'module_name' => "Research Reports", 'guard_name' => "web"],
            ['name' => "portal.research_reports.search", 'description' => "Search Record", 'module' => "research_reports", 'module_name' => "Research Reports", 'guard_name' => "web"],

            ['name' => "portal.cms.roles.index", 'description' => "List of Roles", 'module' => "cms.roles", 'module_name' => "CMS - Roles", 'guard_name' => "web"],
            ['name' => "portal.cms.roles.create", 'description' => "Create New Role", 'module' => "cms.roles", 'module_name' => "CMS - Roles", 'guard_name' => "web"],
            ['name' => "portal.cms.roles.update", 'description' => "Update Role Details", 'module' => "cms.roles", 'module_name' => "CMS - Roles", 'guard_name' => "web"],
            ['name' => "portal.cms.roles.search", 'description' => "Search Record", 'module' => "cms.roles", 'module_name' => "CMS - Roles", 'guard_name' => "web"],

            ['name' => "portal.cms.permissions.index", 'description' => "List of Permissions", 'module' => "cms.permissions", 'module_name' => "CMS - Permissions", 'guard_name' => "web"],
            ['name' => "portal.cms.permissions.search", 'description' => "Search Record", 'module' => "cms.permissions", 'module_name' => "CMS - Permissions", 'guard_name' => "web"],

            ['name' => "portal.cms.departments.index", 'description' => "List of Departments", 'module' => "cms.departments", 'module_name' => "CMS - Departments", 'guard_name' => "web"],
            ['name' => "portal.cms.departments.create", 'description' => "Create New Department", 'module' => "cms.departments", 'module_name' => "CMS - Departments", 'guard_name' => "web"],
            ['name' => "portal.cms.departments.update", 'description' => "Update Department Details", 'module' => "cms.departments", 'module_name' => "CMS - Departments", 'guard_name' => "web"],
            ['name' => "portal.cms.departments.delete", 'description' => "Delete Department", 'module' => "cms.departments", 'module_name' => "CMS - Departments", 'guard_name' => "web"],
            ['name' => "portal.cms.departments.search", 'description' => "Search Record", 'module' => "cms.departments", 'module_name' => "CMS - Departments", 'guard_name' => "web"],

            ['name' => "portal.cms.courses.index", 'description' => "List of Courses", 'module' => "cms.courses", 'module_name' => "CMS - Courses", 'guard_name' => "web"],
            ['name' => "portal.cms.courses.create", 'description' => "Create New Course", 'module' => "cms.courses", 'module_name' => "CMS - Courses", 'guard_name' => "web"],
            ['name' => "portal.cms.courses.update", 'description' => "Update Course Details", 'module' => "cms.courses", 'module_name' => "CMS - Courses", 'guard_name' => "web"],
            ['name' => "portal.cms.courses.delete", 'description' => "Delete Course", 'module' => "cms.courses", 'module_name' => "CMS - Courses", 'guard_name' => "web"],
            ['name' => "portal.cms.courses.search", 'description' => "Search Record", 'module' => "cms.courses", 'module_name' => "CMS - Courses", 'guard_name' => "web"],

            ['name' => "portal.cms.yearlevels.index", 'description' => "List of Yearlevels", 'module' => "cms.yearlevels", 'module_name' => "CMS - Yearlevels", 'guard_name' => "web"],
            ['name' => "portal.cms.yearlevels.create", 'description' => "Create New Yearlevel", 'module' => "cms.yearlevels", 'module_name' => "CMS - Yearlevels", 'guard_name' => "web"],
            ['name' => "portal.cms.yearlevels.update", 'description' => "Update Yearlevel Details", 'module' => "cms.yearlevels", 'module_name' => "CMS - Yearlevels", 'guard_name' => "web"],
            ['name' => "portal.cms.yearlevels.delete", 'description' => "Delete Yearlevel", 'module' => "cms.yearlevels", 'module_name' => "CMS - Yearlevels", 'guard_name' => "web"],
            ['name' => "portal.cms.yearlevels.search", 'description' => "Search Record", 'module' => "cms.yearlevels", 'module_name' => "CMS - Yearlevels", 'guard_name' => "web"],
        
            ['name' => "portal.cms.research_types.index", 'description' => "List of Research Types", 'module' => "cms.research_types", 'module_name' => "CMS - Research Types", 'guard_name' => "web"],
            ['name' => "portal.cms.research_types.create", 'description' => "Create New Research Type", 'module' => "cms.research_types", 'module_name' => "CMS - Research Types", 'guard_name' => "web"],
            ['name' => "portal.cms.research_types.update", 'description' => "Update Research Type Details", 'module' => "cms.research_types", 'module_name' => "CMS - Research Types", 'guard_name' => "web"],
            ['name' => "portal.cms.research_types.delete", 'description' => "Delete Research Type", 'module' => "cms.research_types", 'module_name' => "CMS - Research Types", 'guard_name' => "web"],
            ['name' => "portal.cms.research_types.search", 'description' => "Search Record", 'module' => "cms.research_types", 'module_name' => "CMS - Research Types", 'guard_name' => "web"],
        
            ['name' => "portal.cms.pages.index", 'description' => "List of Pages", 'module' => "cms.pages", 'module_name' => "CMS - Pages", 'guard_name' => "web"],
            ['name' => "portal.cms.pages.view", 'description' => "View Page Details", 'module' => "cms.pages", 'module_name' => "CMS - Pages", 'guard_name' => "web"],
            ['name' => "portal.cms.pages.create", 'description' => "Create New Page", 'module' => "cms.pages", 'module_name' => "CMS - Pages", 'guard_name' => "web"],
            ['name' => "portal.cms.pages.update", 'description' => "Update Page Details", 'module' => "cms.pages", 'module_name' => "CMS - Pages", 'guard_name' => "web"],
            ['name' => "portal.cms.pages.delete", 'description' => "Delete Page", 'module' => "cms.pages", 'module_name' => "CMS - Pages", 'guard_name' => "web"],
            ['name' => "portal.cms.pages.search", 'description' => "Search Record", 'module' => "cms.pages", 'module_name' => "CMS - Pages", 'guard_name' => "web"],

            ['name' => "portal.audit_trail.index", 'description' => "List of Audit Trail", 'module' => "audit_trail", 'module_name' => "Audit Trail", 'guard_name' => "web"],
            ['name' => "portal.audit_trail.export", 'description' => "Generate Report", 'module' => "audit_trail", 'module_name' => "Audit Trail", 'guard_name' => "web"],
            ['name' => "portal.audit_trail.search", 'description' => "Search Record", 'module' => "audit_trail", 'module_name' => "Audit Trail", 'guard_name' => "web"],
        
            ['name' => "portal.notifications.index", 'description' => "List of Notification", 'module' => "notifications", 'module_name' => "Notifications", 'guard_name' => "web"],
            ['name' => "portal.notifications.search", 'description' => "Search Record", 'module' => "notifications", 'module_name' => "Notifications", 'guard_name' => "web"],
        ];

        foreach($permissions as $permission){
            Permission::firstOrCreate(
                ['name' => $permission['name'], 'guard_name' => $permission['guard_name']], $permission
            );
        }
    }
}
