<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCustomerRequest;
use App\Models\Customer;
use App\Models\Room;
use App\Models\RoomStatus;
use App\Models\User;
use App\Repositories\CustomerRepository;
use App\Repositories\ImageRepository;
use Illuminate\Http\Request;
use App\Notifications\NewRoomServiceNotification;
use App\Events\RefreshDashboardEvent;
use App\Events\NewRoomServiceEvent;

class CustomerController extends Controller
{
    private $customerRepository;

    public function __construct(CustomerRepository $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    public function index(Request $request)
    {
        $customers = $this->customerRepository->get($request);
        return view('customer.index', compact('customers'));
    }

    public function create()
    {
        return view('customer.create');
    }

    public function store(StoreCustomerRequest $request)
    {
        $customer = $this->customerRepository->store($request);
        return redirect('customer')->with('success', 'Customer ' . $customer->name . ' created');
    }

    public function show(Customer $customer)
    {
        return view('customer.show', compact('customer'));
    }

    public function edit(Customer $customer)
    {
        return view('customer.edit', ['customer' => $customer]);
    }

    public function update(Customer $customer, StoreCustomerRequest $request)
    {
        $customer->update($request->all());
        return redirect('customer')->with('success', 'customer ' . $customer->name . ' udpated!');
    }

    public function destroy(Customer $customer, ImageRepository $imageRepository)
    {
        try {
            $user = User::find($customer->user->id);
            $avatar_path = public_path('img/user/' . $user->name . '-' . $user->id);

            $customer->delete();
            $user->delete();

            if (is_dir($avatar_path)) {
                $imageRepository->destroy($avatar_path);
            }

            return redirect('customer')->with('success', 'Customer ' . $customer->name . ' deleted!');
        } catch (\Exception $e) {
            $errorMessage = "";
            if($e->errorInfo[0] == "23000") {
                $errorMessage = "Data still connected to other tables";
            }
            return redirect('customer')->with('failed', 'Customer ' . $customer->name . ' cannot be deleted! ' . $errorMessage);
        }
    }

    public function editRoomStatus(Customer $customer, Room $room)
    {

        $roomstatuses = RoomStatus::all();


        return view('roomservice.index', compact('room', 'customer', 'roomstatuses'));

    }

    public function roomService(Request $request, Customer $customer, Room $room)
    {
        $roomStatusId = $request->input('room_status_id');
        $superAdmins = User::whereIn('role', ['Super', 'Admin'])->get();

        if ($roomStatusId == '1') {
            $query = 'room to be cleaned';
            foreach ($superAdmins as $superAdmin) {
                $message = 'Room service notification from ' . $room->number;
                event(new NewRoomServiceEvent($message, $superAdmin));
                $superAdmin->notify(new NewRoomServiceNotification($customer, $room, $query));
            }

            event(new RefreshDashboardEvent("Someone needs room room service"));

            return redirect()->route('roomservice.index', ['customer' => $customer, 'room' => $room])
    ->with('success', 'Room service query sent');

            //return "Clean room service performed.";
        } elseif ($roomStatusId == '2') {
            // Other Room Issue
            $otherIssue = $request->input('other_issue');
            foreach ($superAdmins as $superAdmin) {
                $message = 'Room service notification from ' . $room->number;
                event(new NewRoomServiceEvent($message, $superAdmin));
                $superAdmin->notify(new NewRoomServiceNotification($customer, $room, $otherIssue));
            }

            event(new RefreshDashboardEvent("Someone needs room room service"));

            return redirect()->route('roomservice.index', ['customer' => $customer, 'room' => $room])
    ->with('success', 'Room service query sent');
        }


        return "Invalid room status selected.";
    }

    public function ticket(Customer $customer, Room $room, String $query)
    {
        return view('roomservice.ticket', compact('customer', 'room', 'query'));
    }

    public function order(Customer $customer, Room $room, String $query)
    {
        return view('customer.order', compact('customer', 'room', 'query'));
    }
}
