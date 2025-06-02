<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DefaultData
 *
 * @author User™
 */
class DefaultData {

    //get userlevel
    public function UserLevels() {
        return array(
            "1" => "Super Admin",
            "2" => "Users",
            "3" => "Store Keper",
        );
    }

    //get userlevel
    public function RegistrationType() {
        return array(
            "1" => "New Student (Full Paid)",
            "2" => "New Student (Half Paid)",
            "6" => "New Student (Full Fee)",
            "3" => "Registration for Exam and file Process only",
            "4" => "Registration for file process only",
            "5" => "Registration for exam only",
        );
    }

    //get userlevel
    public function FcwaExam() {
        return array(
            "1" => "1st Attempt",
            "2" => "1st Repeat",
            "3" => "2nd Repeat",
            "4" => "3rd Repeat",
            "5" => "4th Repeat",
        );
    }

    //get gender
    public function gender() {
        return array(
            "1" => "Male - පිරිමි",
            "2" => "Female - ගැහැණු"
        );
    }

    //get branches
    public function Branches() {
        return array(
            "1" => "Kelanimulla",
            "2" => "Anuradhapura",
            "3" => "Gampaha",
            "4" => "Akuressa",
            "5" => "Others"
        );
    }

    //get branches
    public function education() {
        return array(
            "1" => "G.C.E. O/L",
            "2" => "G.C.E A/L",
            "3" => "Diploma",
            "4" => "Degree"
        );
    }

    //get branches
    public function PaymentStatus() {
        return array(
            "1" => "Not Complete Payment",
            "2" => "Payment Complete",
        );
    }

    //get branches
    public function PaymentCategory() {
        return array(
            "1" => "Suppliers",
            "2" => "Daily",
        );
    }

    //get branches
    public function PaymentType() {
        return array(
            "1" => "cash",
            "2" => "check",
            "3" => "credit card",
            "4" => "bank deposit",
            "5" => "Online transfer",
        );
    }

    //get branches
    public function source() {
        return array(
            "1" => "Facebook",
            "2" => "Tick Tok",
            "3" => "News paper"
        );
    }
    //get Designation
    public function Designation() {
        return array(
            "1" => "Accountant",
            "2" => "It Technicioan",
            "3" => "Staff Member"
        );
    }

    //get branches
    public function Iscertificate() {
        return array(
            "1" => "Only Certificate - සහතිකයක් සදහා පමණක්",
            "2" => "Job for Korea - කොරියාවේ රැකියාවක් සදහා"
        );
    }

    //get branches
    public function ExamFee() {
        return array(
            "1" => "28000",
            "2" => "32000"
        );
    }

    function DagType()
    {
        return array(
            "canvas" => "CANVAS", 
            "radial" => "RADIAL",
        );
    }

    function DagMake()
    {
        return array(
            "arpico" => "Arpico", 
            "ceat" => "Ceat",
        );
    }
}
