<?php

class Robot
{
    protected $x = 0;
    protected $xOperator = '+';
    protected $y = 0;
    protected $yOperator = '+';
    protected $finalDirection = self::NORTH;
    protected $command;
    protected $commands = [];
    protected $directions = ['North', 'South', 'East', 'West'];
    protected $walkingCommands;
    protected const NORTH = 0;
    protected const SOUTH = 1;
    protected const EAST = 2;
    protected const WEST = 3;
    protected const RIGHT_TURN = 'R';
    protected const LEFT_TURN = 'L';

    /**
     * @param string $command
     */
    public function __construct(string $command)
    {
        $this->command = $command;
        preg_match_all('/W[0-9]+/', $command, $this->walkingCommands);
    }

    public function handle()
    {
        $this->parseCommand()
            ->executeCommand()
            ->output();
    }

    /**
     * Runs the command that has been parsed
     * @return $this
     */
    private function executeCommand()
    {
        foreach($this->commands as $key => $command){

            if($key == 0 && !in_array($command, [self::RIGHT_TURN,SELF::LEFT_TURN])){
                $checker = $key;
                do{
                    $stepCount = str_replace('W','',$this->commands[$checker]);

                    $this->y = eval("return $this->y + $stepCount;");
                    $checker++;
                }while(!in_array($this->commands[$checker], [self::RIGHT_TURN,SELF::LEFT_TURN]));

                continue;
            }

            if($this->finalDirection == self::NORTH){
                if($command == self::RIGHT_TURN){
                    $this->xOperator = '+';
                    $this->finalDirection = self::EAST;
                }

                elseif($command == SELF::LEFT_TURN){
                    $this->xOperator = '-';
                    $this->finalDirection = self::WEST;
                }

                $this->x += $this->evaluateStepsInDirection($this->xOperator,$key,$this->commands);
                continue;
            }

            if($this->finalDirection == self::WEST){
                if($command == self::RIGHT_TURN){
                    $this->yOperator = '+';
                    $this->finalDirection = self::NORTH;
                }

                elseif($command == SELF::LEFT_TURN){
                    $this->yOperator = '-';
                    $this->finalDirection = self::SOUTH;
                }

                $this->y += $this->evaluateStepsInDirection($this->yOperator,$key,$this->commands);
                continue;
            }

            if($this->finalDirection == self::SOUTH){
                if($command == self::RIGHT_TURN){
                    $this->xOperator = '-';
                    $this->finalDirection = self::WEST;
                }

                elseif($command == SELF::LEFT_TURN){
                    $this->xOperator = '+';
                    $this->finalDirection = self::EAST;
                }

                $this->x += $this->evaluateStepsInDirection($this->xOperator,$key,$this->commands);
                continue;
            }

            if($this->finalDirection == self::EAST){
                if($command == self::RIGHT_TURN){
                    $this->yOperator = '-';
                    $this->finalDirection = self::SOUTH;
                }

                elseif($command == SELF::LEFT_TURN){
                    $this->yOperator = '+';
                    $this->finalDirection = self::NORTH;
                }

                $this->y += $this->evaluateStepsInDirection($this->yOperator,$key,$this->commands);

            }

        }
        return $this;
    }

    /**
     * Prints output to the console
     */
    private function output()
    {
        printf("X: %d Y: %d Direction: %s.",$this->x,$this->y,$this->directions[$this->finalDirection]);
    }

    /**
     * Parses the command recieved from the CLI to a format
     * that the robot can respond tp
     * @return $this
     */
    private function parseCommand()
    {
        for($i = 0; $i < strlen($this->command); $i++){
            switch($this->command[$i]){
                case self::RIGHT_TURN:
                    $this->commands[] = self::RIGHT_TURN;
                    break;
                case SELF::LEFT_TURN:
                    $this->commands[] = SELF::LEFT_TURN;
                    break;
                case 'W':
                    $this->commands[] = array_shift($this->walkingCommands[0]);
                    break;
                default:
                    break;
            }
        }
        unset($this->command);
        unset($this->walkingCommands);
        return $this;
    }

    /**
     * @param $direction
     * @param $key
     * @param $commands
     * @return int|mixed|void
     */
    private function evaluateStepsInDirection($direction, $key, $commands)
    {
        $checker = 1;
        $steps = 0;

        if(!in_array($commands[$key],[self::RIGHT_TURN,SELF::LEFT_TURN])){
            return;
        }

        while(isset($commands[$key+$checker]) && !in_array($commands[$key+$checker],[self::RIGHT_TURN,SELF::LEFT_TURN])){

            $stepCount = str_replace('W','',$commands[$key+$checker]);

            $steps = eval("return $steps $direction  $stepCount;");
            $checker++;
        }

        return $steps;
    }
}