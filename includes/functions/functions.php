<?php

	/*
	** Get All Function v2.0
	** Function To Get All Records From Any Database Table
	*/

    function getAllFrom($fild, $table, $where=null, $and=null, $order, $ordering='DESC', $limt=null, $join=null, $cond=null) {
        global $con;
        $sql = $join == null ? '' : "INNER JOIN $join ON $cond";
        $getAll = $con -> prepare("SELECT $fild FROM $table $sql $where $and ORDER BY $order $ordering $limt");
        $getAll->execute();
        $all = $getAll->fetchAll();
        return $all;
    }

    /*
    ** check fun v2.0
    ** check every thing in db[item , status, ...]
    */

    function check($fild, $table, $where=null, $and=null) {
        global $con;
        $stmt = $con->prepare("SELECT $fild FROM $table $where $and");
        $stmt->execute();
        $check = $stmt -> rowCount();
        return $check;
    }

	/*
	** Title Function v1.0
	** Title Function That Echo The Page Title In Case The Page
	** Has The Variable $pageTitle And Echo Defult Title For Other Pages
	*/

	function getTitle() {

		global $pageTitle;

		if (isset($pageTitle)) {

			echo $pageTitle;

		} else {

			echo 'Default';

		}
	}

//	/*
//	** Count Number Of Items Function v1.0
//	** Function To Count Number Of Items Rows
//	** $item = The Item To Count
//	** $table = The Table To Choose From
//	*/
//
//	function countItems($item, $table) {
//
//		global $con;//to make can use $con var
//
//		$stmt2 = $con->prepare("SELECT COUNT($item) FROM $table");
//
//		$stmt2->execute();
//
//		return $stmt2->fetchColumn(); //value of this column
//
//	}



//////////////////////////////////////////////unesed////////////////////////////////////////////////////////////////////////

    /*
    ** get items fun v2.1
    ** get items from db depind on [id category or id mem]
    ** $where: id [cat_id or mem_id]
    ** $value: value for id
    ** $aprove: if null get only aprove items [aprove=1]
    */
//    function getItems($where, $value, $aprove = null) {
//        global $con;
//        if($aprove == null) {
//            $sql= "And aprove = 1";
//        } else{
//            $sql = null;
//        }
//        $getItems = $con -> prepare("SELECT * FROM items WHERE $where=? $sql ORDER BY itemID DESC");
//        $getItems -> execute(array($value));
//        $items = $getItems->fetchAll();
//        return $items;
//    }


//	/*
//	** Check Items Function v1.0
//	** Function to Check Item In Database [ Function Accept Parameters ]
//	** $select = The Item To Select [ Example: user, item, category ]
//	** $from = The Table To Select From [ Example: users, items, categories ]
//	** $value = The Value Of Select [ Example: Osama, Box, Electronics ]
//    ** return 0 if no item & 1 if exists
//	*/
//
//	function checkItem($select, $from, $value) {
//
//		global $con;
//
//		$statement = $con->prepare("SELECT $select FROM $from WHERE $select = ?");
//
//		$statement->execute(array($value));
//
//		$count = $statement->rowCount();
//
//		return $count;
//
//	}

//	/*
//	** check fun v1.0\
//	** check if user activate (reg status = 1)
//	** fun return 0 if user active and return 1 if noit active
//	** $user user name from session
//	*/
//
//	function checkUserStatus($user) {
//		global $con;
//		$stmt =$con->prepare('SELECT userName,regStatus FROM users WHERE userName=? And regStatus = 0');
//		$stmt->execute(array($user));
//		$row = $stmt -> rowCount();
//		return $row;
//	}

//    /*
//    ** get category fun v1.0
//    ** get all gategory from db
//    */
//    function getCats() {
//        global $con;
//        $getCats = $con -> prepare("SELECT * FROM categories ORDER BY id");
//        $getCats->execute();
//        $cats = $getCats->fetchAll();
//        return $cats;
//    }

//	/*
//	** Get Latest Records Function v1.0
//	** Function To Get Latest Items From Database [ Users, Items, Comments ]
//	** $select = Field To Select
//	** $table = The Table To Choose From
//	** $order = The Desc Ordering
//	** $limit = Number Of Records To Get
//	*/
//
//	function getLatest($select, $table, $order, $limit = 5) {
//
//		global $con;
//
//		$getStmt = $con->prepare("SELECT $select FROM $table ORDER BY $order DESC LIMIT $limit");
//
//		$getStmt->execute();
//
//		$rows = $getStmt->fetchAll();
//
//		return $rows;
//
//}

//	/*
//	** Home Redirect Function v2.0
//	** This Function Accept Parameters
//	** $theMsg = Echo The Message [ Error | Success | Warning ]
//	** $url = The Link You Want To Redirect To
//	** $seconds = Seconds Before Redirecting
//	*/
//
//	function redirectHome($theMsg, $url = null, $seconds = 3) {
//
//		if ($url === null) {
//
//			$url = 'index.php';
//
//			$link = 'Homepage'; //this only to echo the name page
//
//		} else {
//
//			if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== '') {
//
//				$url = $_SERVER['HTTP_REFERER'];
//
//				$link = 'Previous Page';
//
//			} else {
//
//				$url = 'index.php';
//
//				$link = 'Homepage';
//
//			}
//
//		}
//
//		echo $theMsg;
//
//		echo "<div class='alert alert-info'>You Will Be Redirected to $link After $seconds Seconds.</div>";
//
//		header("refresh:$seconds;url=$url");
//
//		exit();
//
//	}